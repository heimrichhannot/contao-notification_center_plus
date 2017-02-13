<?php
/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2016 Heimrich & Hannot GmbH
 *
 * @author  Rico Kaltofen <r.kaltofen@heimrich-hannot.de>
 * @license http://www.gnu.org/licences/lgpl-3.0.html LGPL
 */

namespace HeimrichHannot\NotificationCenterPlus;


class MessageModel extends \NotificationCenter\Model\Message
{
	/**
	 * Find published messages by id
	 * @param mixed $intId   The message id
	 * @param array $arrOptions An optional options array
	 *
	 * @return static The model or null if the result is empty
	 */
	public static function findPublishedById($intId, array $arrOptions = [])
	{
		$t = static::$strTable;

		$arrColumns = ["$t.id=? AND $t.published=1"];
		$arrValues  = [$intId];

		return static::findOneBy($arrColumns, $arrValues, $arrOptions);
	}
	
}