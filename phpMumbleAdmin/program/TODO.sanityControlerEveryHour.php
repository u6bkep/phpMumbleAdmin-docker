<?php

/*
* global sanity controller to performe misc action every hour.
*/

/*
* Rebase keys of an array but keep ordering.
*/

/*
* Remove vserver cache profile without a valid profile id.
*/
if (is_null($PMA->profiles->get($cache['profileID']))) {
    unset($this->datas[$cache['profileID']]);
}
/*
* Remove password request with invalid ICE profile
* SEE : PMA_datas_pwRequests
*/
if (is_null($PMA->profiles->get($pwRequest['profile_id']))) {
    unset($this->datas[$pwRequest['profile_id']]);
}

/*
* Remove admins access without a valid profileID or serverID
*/

/*
* Empty admin lastConn devient null pour le sorting
*/