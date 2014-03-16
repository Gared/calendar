<?php
/**
 * Copyright (c) 2014 Georg Ehrke <oc.list@georgehrke.com>
 * This file is licensed under the Affero General Public License version 3 or
 * later.
 * See the COPYING-README file.
 */
namespace OCA\Calendar\Backend;

interface CalendarInterface {
	/**
	 * @brief Check if backend implements actions
	 * @param string $actions
	 * @returns integer
	 * 
	 * This method returns an integer.
	 * If the action is supported, it returns an integer that can be compared with \OC\Calendar\Backend\CREATE_CALENDAR, etc...
	 * If the action is not supported, it returns -501
	 * This method is mandatory!
	 * This method is fully implemented in \OCA\Calendar\Backend\Backend;
	 */
	public function implementsActions($actions);

	/**
	 * @brief returns whether or not calendars should be cached
	 * @param string $userId
	 * @returns boolean
	 * 
	 * This method returns a boolen. true if calendars should be cached, false if calendars shouldn't be cached
	 * This method is mandatory!
	 */
	public function cacheCalendars($userId);

	/**
	 * @brief returns whether or not calendar objects should be cached
	 * @param string $calendarURI
	 * @param string $userId
	 * @returns boolean
	 * @throws DoesNotExistException if uri does not exist
	 * 
	 * This method returns a boolen. true if calendar objects should be cached, false if the calendar objects shouldn't be cached
	 * This method is mandatory!
	 */
	public function cacheObjects($calendarURI, $userId);

	/**
	 * @brief returns information about calendar $calendarURI of the user $userId
	 * @param string $calendarURI
	 * @param string $userId
	 * @returns array with \OCA\Calendar\Db\Calendar object
	 * @throws DoesNotExistException if uri does not exist
	 * 
	 * This method returns an array of \OCA\Calendar\Db\Calendar object.
	 * This method is mandatory!
	 */
	public function findCalendar($calendarURI, $userId);

	/**
	 * @brief returns all calendars of the user $userId
	 * @param string $userId
	 * @returns array with \OCA\Calendar\Db\Calendar objects
	 * @throws DoesNotExistException if uri does not exist
	 * 
	 * This method returns an array of \OCA\Calendar\Db\Object objects.
	 * This method is mandatory!
	 */
	public function findCalendars($userId, $limit, $offset);

	/**
	 * @brief returns information about the object (event/journal/todo) with the uid $objectURI in the calendar $calendarURI of the user $userId 
	 * @param string $calendarURI
	 * @param string $objectURI
	 * @param string $userid
	 * @returns \OCA\Calendar\Db\Object object
	 * @throws DoesNotExistException if uri does not exist
	 * @throws DoesNotExistException if uid does not exist
	 *
	 * This method returns an \OCA\Calendar\Db\Object object.
	 * This method is mandatory!
	 */
	public function findObject($calendarURI, $objectURI, $userId);

	/**
	 * @brief returns all objects in the calendar $calendarURI of the user $userId
	 * @param string $calendarURI
	 * @param string $userId
	 * @returns array with \OCA\Calendar\Db\Object objects
	 * @throws DoesNotExistException if uri does not exist
	 * 
	 * This method returns an array of \OCA\Calendar\Db\Object objects.
	 * This method is mandatory!
	 */
	public function findObjects($calendarURI, $userId, $limit, $offset);

	public function canBeEnabled();
}