<?php

 /*
 * phpMumbleAdmin (PMA), administration panel for murmur (mumble server daemon).
 * Copyright (C) 2010 - 2016, Dadon David, dev.pma@ipnoz.net
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/> .
 */


/*
***************************************************************
***************************************************************
*
* Main
*
***************************************************************
***************************************************************
*/

define('PMA_HOST_ERROR_REPORTING', error_reporting(-1));

/*
* PMA started microTime.
*/
define('PMA_STARTED', microTime(true));

/*
* Setup some php.ini vars
*
* Disable opcache - this is really annoying for a dynamic web site.
*/
ini_set('opcache.enable', 0);

/*
* Setup paths.
*/
define('PMA_ROOT_PATH', __DIR__ .'/');
define('PMA_DIR_PROG', PMA_ROOT_PATH.'program/');

/*
* Load paths file.
*/
require PMA_DIR_PROG.'includes/def.files.inc';

/*
* Load init file.
*/
require PMA_DIR_INCLUDES.'init.inc';

/*
* Load functions and PMA exceptions.
*/
require PMA_DIR_FUNCTIONS.'misc.php';
require PMA_DIR_FUNCTIONS.'sort.php';
require PMA_DIR_FUNCTIONS.'PMA.php';
require PMA_DIR_FUNCTIONS.'debug.php';
require PMA_DIR_LIB.'PMA_exceptions.php';

/*
* Setup the core object ($PMA).
*/
$PMA = PMA_core::getInstance();

/*
* Setup essentials objects.
*/
$PMA->router = new PMA_router();
$PMA->db = PMA_db::instance(); // Rework required
$PMA->app = new PMA_datas_app();
$PMA->config = new PMA_datas_config();
$PMA->bans = new PMA_datas_bans();
$PMA->cookie = new PMA_cookie();
$PMA->session = new PMA_session();
$PMA->profiles = new PMA_datas_profiles();
$PMA->user = new PMA_user();
$PMA->murmurMeta = new PMA_MurmurMeta();

/*
* Reset php ERROR_REPORTING level if PMA debug is off.
*/
if ($PMA->config->get('debug') === 0) {
    error_reporting(PMA_HOST_ERROR_REPORTING);
}

/*
* Check PHP version (minimum 5.4.0).
*/
if (PHP_VERSION_ID < 50400) {
    $PMA->fatalError(
        'This version of PhpMumbleAdmin requires at least PHP 5.4.0.<br />
        You are currently running PHP <strong>'. PHP_VERSION .'</strong>.
        Please update your PHP version.'
    );
}

/*
* Register shutdown method.
*/
register_shutdown_function(array($PMA, 'shutdown'));

/*
* Check bans
*/
$PMA->debug('Checking your IP: '.$_SERVER['REMOTE_ADDR'], 3);
if ($PMA->bans->checkIP($_SERVER['REMOTE_ADDR'])) {
    $PMA->bans->killPma();
}
$PMA->debug('You can read this message, good for you ! :-D', 3);

/*
* Init cookie.
* Set default options.
*/
$PMA->debug('Setup cookie', 3);
$PMA->cookie->set('profile_id', $PMA->config->get('default_profile'));
$PMA->cookie->set('lang', $PMA->config->get('default_lang'));
$PMA->cookie->set('skin', $PMA->config->get('default_skin'));
$PMA->cookie->set('timezone', $PMA->config->get('default_timezone'));
$PMA->cookie->set('time', $PMA->config->get('default_time'));
$PMA->cookie->set('date', $PMA->config->get('default_date'));
$PMA->cookie->set('installed_localeFormat', $PMA->config->get('defaultSystemLocales'));
$PMA->cookie->set('uptime', $PMA->config->get('default_uptime'));

/*
* No conf cookie found,
* check if user really accept cookie or if it is the first connection to PMA.
*/
if (! $PMA->cookie->loadCookie()) {
    /*
    * Redirect user to check that he accepted the config cookie with
    * the "check cookie url".
    */
    if (! isset($_GET[PMA_cookie::CHECK_URL])) {
        $PMA->cookie->requestUpdate();
        $PMA->redirection('?'.PMA_cookie::CHECK_URL);
    } else {
        // Still no cookie: user don't accept cookies
        $PMA->messageError('refuse_cookies');
        $PMA->messageError('Check again'); // href="./"
    }
}

/*
* INCLUDE PATH
* Add the path for "Ice.php" to include_path.
*/
if ($PMA->config->get('IcePhpIncludePath') !== '') {
    $path = $PMA->config->get('IcePhpIncludePath');
    set_include_path(get_include_path(). PATH_SEPARATOR .$path);
}

/*
* TIMEZONE
* Setup user timezone. Throws a notice error on invalid timezone.
*/
$PMA->debug('Setup timezone', 3);
@date_default_timezone_set($PMA->cookie->get('timezone'));

/*
* LOCALES
*/
$localesProfiles = $PMA->config->get('systemLocalesProfiles');
$userLocale = $PMA->cookie->get('installed_localeFormat');
if (isset($localesProfiles[$userLocale])) {
    setLocale(LC_ALL, $userLocale);
} else {
    setLocale(LC_ALL, $PMA->config->get('defaultSystemLocales'));
}

/*
* SESSION
* Setup PMA session.
*/
$PMA->debug('Setup session', 3);
$PMA->session->setDirectory(PMA_DIR_SESSIONS);
$PMA->session->setCookiePath(PMA_HTTP_PATH);
$PMA->session->setAutoLogout($PMA->config->get('auto_logout') * 60);
if (! $PMA->session->isWritableDir()) {
    $dir = str_ireplace(PMA_ROOT_PATH, '', PMA_DIR_SESSIONS);
    $PMA->fatalError('Session directory <strong>'.$dir.'</strong> is not writeable.');
}
if ($PMA->session->isSsanityRequired($PMA->app->get('lastSessionsCheck'))) {
    $PMA->debug('session sanity...', 1);
    $PMA->session->removeOutdatedSessions();
    $PMA->app->set('lastSessionsCheck', time()); // Update last check timestamp.
}
if ($PMA->cookie->userAcceptCookies()) {
    $PMA->session->start();
}
$PMA->session->initialize();
$PMA->mergeMessages($PMA->session->getMessages());

/*
* ROUTES CONTROLERS
*/
$PMA->debug('Setup routes', 3);
$PMA->router->initialize();
$PMA->router->newController('profile', false, true);
$PMA->router->newController('page');
$PMA->router->newController('tab');
$PMA->router->newController('subtab');
$PMA->router->loadHistoryNoDeep();
$PMA->router->loadHistoryDeep();

/*
* Setup user profile.
*/
$PMA->userProfile = $PMA->profiles->get($PMA->router->getRoute('profile'));


/*
* USER
*/
$PMA->debug('Setup user', 3);
$PMA->user->setProfileID($PMA->router->getRoute('profile'));
$PMA->user->setup();
if ($PMA->user->isPmaAdmin()) {
    $PMA->admins = new PMA_datas_admins();
    $registration = $PMA->admins->get($PMA->user->adminID);
    if (is_null($registration)) {
        $PMA->logout();
        $PMA->debugError('Admin do not exist. Logout.');
        $PMA->redirection();
    }
    // Update admin login & class, it may have changed during two requests.
    $PMA->user->setLogin($registration['login']);
    $PMA->user->setClass($registration['class']);
    if ($PMA->user->is(PMA_USER_ADMIN)) {
        // Update admins access only.
        $PMA->user->setAdminAccess($registration['access']);
    }
}

/*
* Initialize $DATES object.
*/
$DATES = new PMA_dates($PMA->cookie->get('date'), $PMA->cookie->get('time'));
$DATES->setUptimeFormat($PMA->cookie->get('uptime'));

/*
* Setup pages.
*/
$PMA->page = new PMA_page();
$PMA->page->setPath(PMA_DIR_PAGES);

/*
* Setup widgets.
*/
$PMA->widgets = new PMA_modules_widgets();
$PMA->widgets->setPath(PMA_DIR_WIDGETS);
$PMA->widgets->setErrorFile('widgetError.view.php');

/*
* Setup popups.
*/
$PMA->popups = new PMA_modules_popups();
$PMA->popups->setPath(PMA_DIR_POPUPS);


/*
***************************************************************
***************************************************************
*
* Exec external viewer
*
***************************************************************
***************************************************************
*/

if (isset($_GET['ext_viewer'])) {
    require PMA_DIR_INCLUDES.'externalViewer.inc';
    die();
}


/*
***************************************************************
***************************************************************
*
* Exec commands
*
***************************************************************
***************************************************************
*/

if (isset($_GET['cmd']) OR isset($_POST['cmd'])) {
    require PMA_DIR_CMD.'process.php';
}


/*
***************************************************************
***************************************************************
*
* Setup OUTPUT
*
***************************************************************
***************************************************************
*/



$PMA->skeleton = new PMA_skeleton();
$PMA->infoPanel = new PMA_infoPanel();
$PMA->captions = new PMA_captions();

/*
* Load images definitions.
*/
require PMA_DIR_INCLUDES.'def.images.inc';

/*
* Setup routes.
*/
require PMA_DIR_ROUTES.'profiles.php';
require PMA_DIR_ROUTES.'pages.php';
require PMA_DIR_ROUTES.$PMA->router->getRoute('page').'.php';

/*
* Check for Ice-PHP 3.4 workaround after routes setup.
*/
require PMA_FILE_ICE34_WORKAROUND;

/*
* Setup current user who online widget.
*/
if ($PMA->user->isMinimum(PMA_USER_UNAUTH)) {
    $PMA->whosOnline = new PMA_datas_whosOnline();
    $PMA->whosOnline->removeOldActivity();
    $PMA->whosOnline->setAutoLogout($PMA->config->get('auto_logout') * 60);
    $PMA->whosOnline->updateUser($PMA->user);
}

/*
* Load languages for the current page.
*/
pmaLoadLanguage('common');
pmaLoadLanguage($PMA->router->getRoute('page'));

/*
* Add common CSS files.
*/
$PMA->skeleton->addCssFile('classes.css');
$PMA->skeleton->addCssFile('main.css');
$PMA->skeleton->addCssFile('themes/'.$PMA->cookie->get('skin'));

/*
* Add common JS text.
*/
$PMA->skeleton->addJsText('pw_check_failed', $TEXT['password_check_failed']);
$PMA->skeleton->addJsText('invalid_ip', $TEXT['invalid_IP_address']);
$PMA->skeleton->addJsText('invalid_port', $TEXT['invalid_port']);
$PMA->skeleton->addJsText('invalid_number', $TEXT['invalid_numerical']);

/*
* Setup main widgets.
*/
$PMA->widgets->newModule('languagesFlags');
$PMA->widgets->newModule('logout');
$PMA->widgets->newModule('route_pages');
$PMA->widgets->newModule('route_profiles');
$PMA->widgets->newModule('messages');
$PMA->widgets->newModule('serversPanel');
$PMA->widgets->newModule('infoPanel');
$PMA->widgets->newModule('route_tabs');
$PMA->widgets->newModule('route_subTabs');
$PMA->widgets->newModule('whosOnline');
$PMA->widgets->newModule('debug_footer');
$PMA->widgets->newModule('captions');

/*
* Setup misc variables.
*/
$PMA->skeleton->siteTitleEnc = htEnc($PMA->config->get('siteTitle'));
$PMA->skeleton->siteCommentEnc = htEnc($PMA->config->get('siteComment'));
$PMA->skeleton->footerDate = strftime('%A '). $DATES->strDateTime();

/*
* Setup page.
*/
try {
    $page = new PMA_module();
    foreach ($PMA->page->getClasses() as $path) {
        $PMA->debug('Loading page class '.$PMA->page->getID(), 3);
        require $path;
    }
    foreach ($PMA->page->getControllers() as $path) {
        $PMA->debug('Loading page controller '.$PMA->page->getID(), 3);
        require $path;
    }
} catch (PMA_pageException $e) {
    if ($e->getMessage() !== '') {
        $PMA->page->setError($e->getMessage());
        $PMA->page->setView('pageError');
    }
} catch (Exception $e) {
    pmaExceptionsOperations($e);
}

/*
* Setup widgets.
*/
foreach ($PMA->widgets->getList() as $oWidget) {
    $PMA->debug('Loading widget '.$oWidget->id, 3);
    try {
        if (is_null($widget = $oWidget->datas)) {
            $widget = new PMA_module();
        }
        if (is_readable($oWidget->controllerPath)) {
            require $oWidget->controllerPath;
        }
        $PMA->widgets->saveDatas($oWidget->id, $widget);
    } catch (PMA_widgetException $e) {
        //$PMA->debugError($oWidget->type.' '.$oWidget->id);
        $PMA->widgets->disable($oWidget->id);
    } catch (Exception $e) {
        //$PMA->debugError($oWidget->type.' '.$oWidget->id);
        $PMA->widgets->disable($oWidget->id);
        pmaExceptionsOperations($e);
    }
}

/*
* Setup popups.
*/
foreach ($PMA->popups->getList() as $oWidget) {
    $PMA->debug('Loading widget '.$oWidget->id, 3);
    try {
        if (is_null($widget = $oWidget->datas)) {
            $widget = new PMA_module();
        }
        if (is_readable($oWidget->controllerPath)) {
            require $oWidget->controllerPath;
        }
        $PMA->popups->saveDatas($oWidget->id, $widget);
    } catch (PMA_widgetException $e) {
        //$PMA->debugError($oWidget->type.' '.$oWidget->id);
        $PMA->popups->disable($oWidget->id);
    } catch (Exception $e) {
        //$PMA->debugError($oWidget->type.' '.$oWidget->id);
        $PMA->popups->disable($oWidget->id);
        pmaExceptionsOperations($e);
    }
}

/*
* Enable not hidden popup as main view if no page view are available.
*/
if (is_null($PMA->page->getViewPath())) {
    foreach ($PMA->popups->getList() as $obj) {
        $PMA->page->setAltViewPath($obj->viewPath);
        break;
    }
}

/*
* Update referer only before output.
*/
$PMA->session->updateReferer();

/*
* Shutdown PMA.
*/
$PMA->shutdown();

/*
***************************************************************
***************************************************************
*
* Exec OUTPUT
*
***************************************************************
***************************************************************
*/

?>
<!DOCTYPE html>

<html>
<!-- <html manifest="cache.manifest.txt"> -->

    <head>

        <meta charset="utf-8" />
        <title><?= $PMA->skeleton->siteTitleEnc; ?></title>
        <meta name="description" content="<?= $PMA->skeleton->siteCommentEnc; ?>" />
        <meta name="generator" content="phpMumbleAdmin" />
        <meta name="robots" content="noindex,nofollow">
        <meta name="referrer" content="origin">
        <link rel="icon" href="images/mumble/mumble.png" />
<?php foreach ($PMA->skeleton->getCssFiles() as $file): ?>
        <link rel="stylesheet" type="text/css" href="css/<?= htEnc($file); ?>" />
<?php endforeach; ?>
        <script src="js/common.js" type="text/javascript"></script>
        <script src="js/drag.js" type="text/javascript"></script>
        <script src="js/expand.js" type="text/javascript"></script>
        <script src="js/helpers.js" type="text/javascript"></script>
        <script src="js/popup.js" type="text/javascript"></script>
        <script src="js/validators.js" type="text/javascript"></script>
        <script type="text/javascript">
            var PMA_COOKIE_ENCRYPTED = <?= (PMA_COOKIE_ENCRYPTED) ? 'true':'false'; ?>;
            var PMA_IMG_TOGGLE_IN_16 = '<?= PMA_IMG_TOGGLE_IN_16; ?>';
            var PMA_IMG_TOGGLE_OUT_16 = '<?= PMA_IMG_TOGGLE_OUT_16; ?>';
            TEXT = new Object();
<?php foreach ($PMA->skeleton->getJsTexts() as $js): ?>
            TEXT.<?= $js->id; ?> = "<?= $js->text; ?>";
<?php endforeach; ?>
        </script>

    </head>

    <body>

        <div id="jsPopups">
<?php foreach ($PMA->popups->getHiddens() as $obj): ?>
            <div hidden="hidden">
<?php require $obj->viewPath; ?>
            </div>
<?php endforeach; ?>
        </div>

        <header id="PMA_header">

<?php require $PMA->widgets->getViewPath('languagesFlags'); ?>

            <h1><?= $PMA->skeleton->siteTitleEnc; ?></h1>
            <h3><?= $PMA->skeleton->siteCommentEnc; ?></h3>

<?php require $PMA->widgets->getViewPath('logout'); ?>

<?php require $PMA->widgets->getViewPath('route_pages'); ?>

        </header>

<?php require $PMA->widgets->getViewPath('route_profiles'); ?>

<?php require $PMA->widgets->getViewPath('messages'); ?>

        <div id="PMA_body">
            <div id="PMA_bodyPanel">
<?php require $PMA->widgets->getViewPath('serversPanel'); ?>
            </div>

<?php require $PMA->widgets->getViewPath('infoPanel'); ?>

<?php require $PMA->widgets->getViewPath('route_tabs'); ?>

            <main>
                <div class="inside">

<?php require $PMA->page->getViewPath(); ?>

                </div><!-- inside - END -->

<?php require $PMA->widgets->getViewPath('captions'); ?>

            </main>
        </div><!-- PMA_body - END -->

<?php require $PMA->widgets->getViewPath('whosOnline'); ?>

        <footer id="PMA_footer">
            <p>
                <?= $PMA->skeleton->footerDate, PHP_EOL; ?>
            </p>
            <p>
                Powered by
                <a href="<?= PMA_PROJECT_URL; ?>"><?= PMA_PROJECT_NAME; ?></a>
<?php if ($PMA->user->isMinimum(PMA_USER_ROOTADMIN)): ?>
                <?= PMA_VERSION_FULL, PHP_EOL; ?>
<?php endif; ?>
            </p>
        </footer>

<?php require $PMA->widgets->getViewPath('debug_footer'); ?>

    </body>

</html>
