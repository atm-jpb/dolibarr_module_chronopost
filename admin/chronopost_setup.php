<?php
/* <one line to give the program's name and a brief idea of what it does.>
 * Copyright (C) 2015 ATM Consulting <support@atm-consulting.fr>
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

/**
 * 	\file		admin/chronopost.php
 * 	\ingroup	chronopost
 * 	\brief		This file is an example module setup page
 * 				Put some comments here
 */
// Dolibarr environment
$res = @include("../../main.inc.php"); // From htdocs directory
if (! $res) {
    $res = @include("../../../main.inc.php"); // From "custom" directory
}

// Libraries
require_once DOL_DOCUMENT_ROOT . "/core/lib/admin.lib.php";
require_once '../lib/chronopost.lib.php';

// Translations
$langs->load("chronopost@chronopost");

// Access control
if (! $user->admin) {
    accessforbidden();
}

// Parameters
$action = GETPOST('action', 'alpha');

/*
 * Actions
 */
if ($action === 'set_chronopost_host_infos') {
	
	foreach($_REQUEST['TConst'] as $code=>$value) {
		dolibarr_set_const($db, $code, $value, 'chaine', 0, '', $conf->entity);
	}
	
	setEventMessage('ChronopostFTPInfosCorrectlySaved');
	header("Location: ".$_SERVER["PHP_SELF"]);
	exit;
	
}
	
if (preg_match('/del_(.*)/',$action,$reg))
{
	$code=$reg[1];
	if (dolibarr_del_const($db, $code, 0) > 0)
	{
		Header("Location: ".$_SERVER["PHP_SELF"]);
		exit;
	}
	else
	{
		dol_print_error($db);
	}
}

/*
 * View
 */
$page_name = "chronopostSetup";
llxHeader('', $langs->trans($page_name));

// Subheader
$linkback = '<a href="' . DOL_URL_ROOT . '/admin/modules.php">'
    . $langs->trans("BackToModuleList") . '</a>';
print_fiche_titre($langs->trans($page_name), $linkback);

// Configuration header
$head = chronopostAdminPrepareHead();
dol_fiche_head(
    $head,
    'settings',
    $langs->trans("Module104981Name"),
    0,
    "chronopost@chronopost"
);

// Setup page goes here
$form=new Form($db);
$var=false;

print '<form method="POST" action="'.$_SERVER['PHP_SELF'].'">';
print '<input type="hidden" name="token" value="'.$_SESSION['newtoken'].'">';
print '<input type="hidden" name="action" value="set_chronopost_host_infos">';

print '<table class="noborder" width="100%">';
print '<tr class="liste_titre">';
print '<td>'.$langs->trans("Parameters").'</td>'."\n";
print '<td align="center" width="20">&nbsp;</td>';
print '<td align="center" width="100">'.$langs->trans("Value").'</td>'."\n";

$var=!$var;
print '<tr '.$bc[$var].'>';
print '<td>'.$langs->trans("ChronopostOnlyInDocuments").'</td>';
print '<td align="center" width="20">&nbsp;</td>';
print '<td align="right" width="300">';
print $form->selectyesno('TConst[CHRONOPOST_ONLY_IN_DOCUMENTS]', $conf->global->CHRONOPOST_ONLY_IN_DOCUMENTS, 1);
print '</td></tr>';

/* TODO La partie EDI est à terminer (à tester avec un vrai serveur ftp)

$var=!$var;
print '<tr '.$bc[$var].'>';
print '<td>'.$langs->trans("ChronopostFTPHost").'</td>';
print '<td align="center" width="20">&nbsp;</td>';
print '<td align="right" width="300">';
print '<input type="text" name="TConst[CHRONOPOST_FTP_HOST]" value="'.$conf->global->CHRONOPOST_FTP_HOST.'" />';
print '</td></tr>';

$var=!$var;
print '<tr '.$bc[$var].'>';
print '<td>'.$langs->trans("ChronopostFTPLogin").'</td>';
print '<td align="center" width="20">&nbsp;</td>';
print '<td align="right" width="300">';
print '<input type="text" name="TConst[CHRONOPOST_FTP_LOGIN]" value="'.$conf->global->CHRONOPOST_FTP_LOGIN.'" />';
print '</td></tr>';

$var=!$var;
print '<tr '.$bc[$var].'>';
print '<td>'.$langs->trans("ChronopostFTPPassword").'</td>';
print '<td align="center" width="20">&nbsp;</td>';
print '<td align="right" width="300">';
print '<input type="password" name="TConst[CHRONOPOST_FTP_PASSWORD]" value="'.$conf->global->CHRONOPOST_FTP_PASSWORD.'" />';
print '</td></tr>';

$var=!$var;
print '<tr '.$bc[$var].'>';
print '<td>'.$langs->trans("ChronopostFTPPort").'</td>';
print '<td align="center" width="20">&nbsp;</td>';
print '<td align="right" width="300">';
print '<input type="text" name="TConst[CHRONOPOST_FTP_PORT]" value="'.$conf->global->CHRONOPOST_FTP_PORT.'" />';
print '</td></tr>';

*/

print '</table>';

print '<div class="tabsAction">';
print '<input class="butAction" type="SUBMIT" name="btSave" value="'.$langs->trans('Save').'" />';
print '</div>';

print '</form>';

llxFooter();

$db->close();