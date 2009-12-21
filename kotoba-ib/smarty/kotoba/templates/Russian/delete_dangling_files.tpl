{* Smarty *}
{*************************************
 * Этот файл является частью Kotoba. *
 * Файл license.txt содержит условия *
 * распространения Kotoba.           *
 *************************************
 *********************************
 * This file is part of Kotoba.  *
 * See license.txt for more info.*
 *********************************}
{*
Код страницы удаления висячих файлов.

Описание переменных:
    $DIR_PATH - путь от корня документов к директории, где хранится index.php
		(см. config.default).
	$STYLESHEET - стиль оформления (см. config.default).
	$uploads - информация о висячих файлах.
	$delete_count - количество висячих файлов, которое было удалено.
*}
{include file='header.tpl' page_title='Удаление висячих файлов' DIR_PATH=$DIR_PATH STYLESHEET=$STYLESHEET}
{if isset($delete_count)}
	Было удалено {$delete_count} висячих файлов.
{else}
	<form action="{$DIR_PATH}/admin/delete_dangling_files.php" method="post" enctype="text/html">
	<table border="1">
	<tbody>
	<tr>
	<td><input type="submit" name="submit" id="submit" value="Удалить"></td>
	<td><input type="checkbox" name="delete_all" id="delete_all" value="1">Все</td>
	</tr>
	{section name=i loop=$uploads}
		{if isset($uploads[i].flag)}
			<tr>
			<td><input type="checkbox" name="delete_{$uploads[i].id}" id="delete_{$uploads[i].id}" value="1"></td>
			{if isset($uploads[i].is_embed)}
				<td>{$uploads[i].link}</td>
			{else}
				<td><a target="_blank" href="{$uploads[i].link}"><img src="{$uploads[i].thumbnail}" class="thumb" width="{$uploads[i].thumbnail_w}" height="{$uploads[i].thumbnail_h}"></a></td>
			{/if}
			</tr>
		{/if}
	{/section}
	</tbody>
	</table>
	</form>
{/if}
{include file='footer.tpl'}