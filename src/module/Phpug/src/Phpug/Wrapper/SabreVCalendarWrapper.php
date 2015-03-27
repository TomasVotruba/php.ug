<?php
/**
 * Copyright (c)2014-2014 heiglandreas
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIBILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @category 
 * @author    Andreas Heigl<andreas@heigl.org>
 * @copyright ©2014-2014 Andreas Heigl
 * @license   http://www.opesource.org/licenses/mit-license.php MIT-License
 * @version   0.0
 * @since     28.11.14
 * @link      https://github.com/heiglandreas/
 */

namespace Phpug\Wrapper;

use Sabre\VObject\Component\VCalendar;
use Sabre\VObject\Component\VEvent;

class SabreVCalendarWrapper implements IcalendarDataWrapperInterface
{

    protected $object = null;

    public function __construct(VCalendar $calendar)
    {
        $this->object = $calendar;
    }

    /**
     * This method creates a valid Ialendar-File
     *
     * @return string
     */
    public function serialize()
    {
        return $this->object->serialize();
    }

    /**
     * @return Event[]
     */
    public function getEvents(\DateInterval $interval)
    {
        $now  = new \DateTime();
        $then = (new \DateTime())->add($interval);
        $this->object->expand($now, $then);
        $return = array();
        foreach ($this->object->children as $item) {
            if (! $item instanceof VEvent) {
                continue;
            }
            $return[] = Event::factory($item);
        }

        usort($return, function($a, $b){
            if ($a->getstartDate() < $b->getStartDate()) return -1;
            if ($a->getstartDate() > $b->getStartDate()) return 1;

            return 0;
        });

        return $return;
    }
}