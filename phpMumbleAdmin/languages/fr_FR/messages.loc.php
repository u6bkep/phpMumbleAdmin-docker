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
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

$TEXT['ice_error'] = 'Erreur fatale ICE';
$TEXT['ice_error_unauth'] = 'Nous avons rencontré une erreur avec ICE.<br />Seul les admins système peuvent se connecter pour le moment.';
$TEXT['ice_error_common'] = 'PhpMumbleAdmin n\'a pas réussi se connecter sur l\'interface ICE.';

// Explicit Ice errors
$TEXT['phpIce_version_not_supported'] = 'La version du module php-Ice n\'est pas supportée.<br />Une version 3.4 ou supérieur est requise.';
$TEXT['ice_module_not_found'] = 'Module php-Ice non trouvé';
$TEXT['Ice_ConnectionRefusedException'] = 'Connexion refusée';
$TEXT['Ice_ConnectTimeoutException'] = 'Délais de connexion expiré';
$TEXT['ice_no_slice_definition_found'] = 'Aucune définition slice trouvée.<br />Veuillez selectionner un fichier slice-php.';
$TEXT['Murmur_InvalidSecretException'] = 'Le mot de passe est incorrect';
$TEXT['ice_invalid_slice_file'] = 'Le fichier des définitions slice est invalide';
$TEXT['ice_could_not_load_Icephp_file'] = 'Le fichier "Ice.php" est introuvable';
$TEXT['ice_invalid_murmur_version'] = 'Version murmur invalide';
$TEXT['Ice_DNSException'] = 'Hôte distant non trouvé';
$TEXT['Ice_UnknownErrorException'] = 'Erreur inconnue avec Ice';

$TEXT['iceprofiles_admin_none'] = 'Vous n\'avez accès à aucun serveur. Veuillez en référer à votre admin';

// Messages
$TEXT['Murmur_InvalidChannelException'] = 'Ce salon n\'existe pas.';
$TEXT['Murmur_InvalidServerException'] = 'Ce serveur n\'existe pas.';
$TEXT['Murmur_InvalidSecretException_write'] = 'Vous n\'avez pas les droits en écriture ICE<br />Vous devez spécifier le mot de passe "icesecretwrite" à PMA';
$TEXT['Murmur_InvalidSessionException'] = 'Cet utilisateur n\'est pas connecté.';
$TEXT['Murmur_InvalidUserException'] = 'Ce compte mumble n\'existe pas.';
$TEXT['Murmur_NestingLimitException'] = 'Limite d\'imbrication des salons atteinte';
$TEXT['Murmur_ServerBootedException'] = 'Le serveur a été arreté durant la mise à jour. La dernière action a été annulée.';
$TEXT['Murmur_ServerFailureException'] = 'Le serveur n\'a pas pu démarrer.<br />Veuillez vérifier les logs du serveur';
$TEXT['Murmur_UnknownException'] = 'Une erreur inconnue est survenue avec ICE';
$TEXT['auth_error'] = 'Echec d\'authentification';
$TEXT['auth_su_disabled'] = 'Connexion SuperUser désactivée';
$TEXT['auth_ru_disabled'] = 'Connexion utilisateur désactivée';
$TEXT['change_pw_error'] = 'Echec - le mot de passe n\'a pas changé';
$TEXT['change_pw_success'] = 'Le mot de passe à été changé avec succès';
$TEXT['invalid_bitmask'] = 'Masque invalide';
$TEXT['invalid_channel_name'] = 'Charactère pour salon invalide';
$TEXT['invalid_username'] = 'Charactère pour utilisateur invalide';
$TEXT['invalid_certificate'] = 'Le certificat soumis est invalide.';
$TEXT['auth_vserver_stopped'] = 'Le serveur est arrêté pour le moment. Veuillez réessayer plus tard.';
$TEXT['user_already_registered'] = 'L\'utilisateur est déja enregistré. Il doit se reconnecter pour que son status passe en mode authentifié';
$TEXT['children_channel'] = 'Vous ne pouvez pas déplacer un salon vers un salon enfant';
$TEXT['username_exists'] = 'Ce nom d\'utilisateur existe déjà';
$TEXT['gen_pw_mail_sent'] = 'Un email de confirmation a été envoyé à votre adresse.<br />Veuillez en suivre les instructions pour générer un nouveau mot de passe.';
$TEXT['web_access_disabled'] = 'L\'accès web au serveur est désactivé';
$TEXT['vserver_dont_allow_HTML'] = 'Le serveur n\'autorise pas les tags HTML';
$TEXT['please_authenticate'] = 'Veuillez vous authentifier';
$TEXT['iceProfile_sessionError'] = 'Une erreur est survenue durant votre session - Pour des raisons de sécurité, veuillez vous ré-authentifier';
$TEXT['gen_pw_authenticated'] = 'Vous ne pouvez pas procéder à une génération de mot de passe en étant authentifié. Veuillez vous déconnecter et réessayer';
$TEXT['certificate_modified_success'] = 'Le certificat a été mis à jour avec succès<br />Vous devez redémarrer le serveur pour le prendre en compte';
$TEXT['host_modified_success'] = 'Le paramètre host a été mise à jour avec succès<br />Vous devez redémarrer le serveur pour le prendre en compte';
$TEXT['port_modified_success'] = 'Le port a été mis à jour avec succès<br />Vous devez redémarrer le serveur pour le prendre en compte';
$TEXT['illegal_operation'] = 'Opération illégale';
$TEXT['vserver_reset_success'] = 'La configuration du serveur a été réinitialisée avec succès';
$TEXT['new_su_pw'] = 'Nouveau mot de passe pour le SuperUser: %s'; // %s = new SuperUser password
$TEXT['registration_deleted_success'] = 'Le compte a été supprimé avec succès';
$TEXT['gen_pw_error'] = 'Un erreur est survenue et PMA ne peut pas traiter votre demande de génération de mot de passe';
$TEXT['gen_pw_invalid_server_id'] = 'Le serveur n\'existe pas et PMA ne peut pas traiter votre demande de génération de mot de passe';
$TEXT['gen_pw_invalid_username'] = 'Le pseudo n\'existe pas et PMA ne peut pas traiter votre demande de génération de mot de passe';
$TEXT['gen_pw_su_denied'] = 'Vous ne pouvez pas faire de demande de génération de mot de passe pour le compte SuperUser';
$TEXT['gen_pw_empty_email'] = 'Aucune adresse email trouvée pour le compte et PMA ne peut pas traiter votre requete de génération de mot de passe';
$TEXT['new_pma_version'] = 'PhpMumbleAdmin %s est disponible'; // %s = new PMA version
$TEXT['no_update_found'] = 'Aucune mise à jour disponible';
$TEXT['registration_created_success'] = 'Le compte a été créé avec succès';
$TEXT['Ice_MemoryLimitException_logs'] = 'Les logs du serveur sont trop volumineux.<br />Veuillez signaler à votre admin de diminuer les requêtes de logs.<br />En attendant, PMA à forcé une requête de 100 lignes.';
$TEXT['vserver_created_success'] = 'Le serveur virtuel a été créé avec succès';
$TEXT['vserver_deleted_success'] = 'Le serveur virtuel a été supprimé avec succès';
$TEXT['refuse_cookies'] = 'Les cookies sont désactivés.<br />Il est nécessaire de les accepter pour pouvoir vous authentifier.';
$TEXT['parameters_updated_success'] = 'Tous les serveurs ont été configurés avec succès';
$TEXT['vserver_offline'] = 'Le serveur est arrêté.';
$TEXT['vserver_offline_info'] = 'Vous ne pouvez pas configurer les salons, les comptes ou les bans tant que le serveur est arrêté.';
