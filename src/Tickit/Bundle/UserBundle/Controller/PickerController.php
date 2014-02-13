<?php

/*
 * Tickit, an open source web based bug management tool.
 *
 * Copyright (C) 2014  Tickit Project <http://tickit.io>
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

namespace Tickit\Bundle\UserBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Picker controller.
 *
 * Responsible for serving requests related to the user picker.
 *
 * @package Tickit\Bundle\UserBundle\Controller
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
class PickerController
{
    /**
     * Find action.
     *
     * Finds matches for the picker search term. The search term
     * must be at least 3 characters long
     *
     * @param Request $request The request object
     *
     * @throws NotFoundHttpException
     */
    public function findAction(Request $request)
    {
        $term = $request->get('term');
        if (empty($term) || strlen($term) < 3) {
            throw new NotFoundHttpException();
        }
    }
}
