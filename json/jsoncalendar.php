<?php
/**
 * Copyright (c) 2014 Georg Ehrke <oc.list@georgehrke.com>
 * This file is licensed under the Affero General Public License version 3 or
 * later.
 * See the COPYING-README file.
 * 
 * Example output:
 * ```json
 * {
 *   "displayname" : "Work",
 *   "calendarURI" : "local-work",
 *   "owner" : {
 *     "userid" : "developer42",
 *     "displayname" : "developer42"
 *   },
 *   "ctag" : 0,
 *   "url" : "https://owncloud/index.php/apps/calendar/calendars/local-work",
 *   "color" : "#000000",
 *   "order" : 0,
 *   "enabled" : true,
 *   "components" : {
 *     "vevent" : true,
 *     "vjournal" : false,
 *     "vtodo" : true
 *   },
 *   "timezone" : {
 *     "stdOffset" : 3600,
 *     "dstOffset" : 7200,
 *     "name" : "Europe/Berlin"
 *   },
 *   "user" : {
 *     "userid" : "developer42",
 *     "displayname" : "developer42"
 *   },
 *   "cruds" : {
 *     "create" : true,
 *     "update" : true,
 *     "delete" : true,
 *     "code" : 31,
 *     "read" : true,
 *     "share" : true
 *   }
 * }
 * ```
 */
namespace OCA\Calendar\JSON;

use \OCA\Calendar\Db\Calendar;
use \OCA\Calendar\Db\ObjectType;
use \OCA\Calendar\Db\Permissions;

class JSONCalendar extends JSON{

	public $calendarURI;
	public $url;
	public $user;
	public $owner;
	public $displayname;
	public $ctag;
	public $color;
	public $order;
	public $components;
	public $timezone;
	public $enabled;
	public $cruds;

	private $calendarObject;

	/**
	 * @brief init JSONCalendar object with data from Calendar object
	 * @param Calendar $calendar
	 */
	public function __construct(Calendar $calendar) {
		$this->properties = array(
			'displayname',
			'enabled',
			'color',
			'ctag',
			'order',
		);
		parent::__construct($calendar);

		//some type fixes
		$this->enabled = (bool) $this->enabled;
		$this->ctag = (int) $this->ctag;
		$this->order = (int) $this->order;

		$this->setCalendarURI();
		$this->setURL();
		$this->setUser();
		$this->setOwner();		
		$this->setComponents();
		$this->setTimezone();
		$this->setCruds();
	}

	/**
	 * @brief get json-encoded string containing all information
	 */
	public function serialize() {
		return json_encode(array(
			'calendarURI'	=> $this->calendarURI,
			'url'			=> $this->url,
			'user'			=> $this->user,
			'owner'			=> $this->owner,
			'displayname'	=> $this->displayname,
			'ctag'			=> $this->ctag,
			'color'			=> $this->color,
			'order'			=> $this->order,
			'components'	=> $this->components,
			'timezone'		=> $this->timezone->serializeJSON(),
			'enabled'		=> $this->enabled,
			'cruds'			=> $this->cruds,
		));
	}

	/**
	 * @brief set public calendar uri
	 */
	private function setCalendarURI() {
		$backend = $this->object->getBackend();
		$uri = $this->object->getUri();

		$this->calendarURI = strtolower($backend . '-' . $uri);
	}

	/**
	 * @brief set api url to calendar
	 */
	private function setURL() {
		$properties = array(
			'calendarId' => $this->calendarURI,
		);

		$url = \OCP\Util::linkToRoute('calendar.calendars.show', $properties);
		$this->url = \OCP\Util::linkToAbsolute('', substr($url, 1));
	}

	/**
	 * @brief set user info
	 */
	private function setUser() {
		$userId = $this->object->getUserId();
		$this->user = $this->getUserInfo($userId);
	}

	/**
	 * @brief set owner info
	 */
	private function setOwner() {
		$ownerId = $this->object->getOwnerId();
		$this->owner = $this->getUserInfo($ownerId);
	}

	/**
	 * @brief return array with user info
	 * @param string $userId
	 * @return array
	 */
	private function getUserInfo($userId=null){
		if($userId === null) {
			$userId = \OCP\User::getUser();
		}
		return array(
			'userid' => $userId,
			'displayname' => \OCP\User::getDisplayName($userId),
		);
	}

	/**
	 * @brief set components info
	 */
	private function setComponents() {
		$components = (int) $this->object->getComponents();

		$this->components = array(
			'vevent'	=> (bool) ($components & ObjectType::EVENT),
			'vjournal'	=> (bool) ($components & ObjectType::JOURNAL),
			'vtodo'		=> (bool) ($components & ObjectType::TODO),
		);
	}

	/**
	 * @brief set timezone info
	 */
	private function setTimezone($timezoneId='UTC') {
		$timezoneId = $this->object->getTimezone();
		//todo - implement
	}

	/**
	 * @brief set cruds info
	 */
	private function setCruds() {
		$cruds = (int) $this->object->getCruds();
		$this->cruds = array(
			'code' => 	$cruds,
			'create' =>	(bool) ($cruds & Permissions::CREATE),
			'read' => 	(bool) ($cruds & Permissions::READ),
			'update' =>	(bool) ($cruds & Permissions::UPDATE),
			'delete' =>	(bool) ($cruds & Permissions::DELETE),
			'share' =>	(bool) ($cruds & Permissions::SHARE),
		);
	}
}