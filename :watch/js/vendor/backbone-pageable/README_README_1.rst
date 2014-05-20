backbone-pageable
=================

|travis-status|_

A pageable, drop-in replacement for Backbone.Collection inspired by
`Backbone.Paginator <https://github.com/addyosmani/backbone.paginator/>`_, but
much better.

.. contents:: Table of Contents

Advantages
----------

Supports client-side and server-side operations
  You can initialize ``Backbone.PageableCollection`` to paginate and/or sort on
  the client-side, server-side or both.
Infinite paging
  Many public APIs like `Github <http://developer.github.com/v3/#pagination>`_
  or `Facebook
  <https://developers.facebook.com/docs/reference/api/pagination/>`_ support
  infinite paging, ``Backbone.PageableCollection`` can handle them easily.
Comes with reasonable defaults
  Server API parameters preconfigured to work with most Rails RESTful APIs by
  default.
Works well with existing server-side APIs
  Query parameter mappings are all configurable, and you can use either 0-based
  or 1-based indices.
Bi-directional event handling
  In client-mode, any changes done on one page is immediately reflected on the
  others with the appropriate events propagated.
100% compatible with existing code
  ``Backbone.PageableCollection`` is a strict superset of
  ``Backbone.Collection`` and passes its `test suite
  <http://wyuenho.github.com/backbone-pageable/test/index.html>`_.
Well tested
  Comes with 100s of tests in addition to the ``Backbone.Collection`` test
  suite.
Well documented
  Use cases and functionality are thoroughly documented.
No surprising behavior
  ``Backbone.PageableCollection`` performs internal state sanity checks at
  appropriate times, so it is next to impossible to get into a weird state.
Light-weight
  Less than 2.9k minified and gzipped.


Installation
------------

Installing from Node.js
+++++++++++++++++++++++

.. code-block:: bash

  npm install backbone-pageable


Installing from Bower
+++++++++++++++++++++

.. code-block:: bash

  bower install backbone-pageable


Browser
+++++++

.. code-block:: html

  <script src="underscore.js"></script>
  <script src="backbone.js"></script>
  <script src="backbone-pageable.js"></script>


Getting to the Backbone.PageableCollection Class from Node.js and AMD
+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

.. code-block:: javascript

  var PageableCollection = require("backbone-pageable");


Getting to the Backbone.PageableCollection Class in the Browser
+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

.. code-block:: javascript

  var PageableCollection = Backbone.PageableCollection;


Introduction
------------

Like Backbone.Collection, you can provide a URL endpoint, configure your initial
pagination state and server API mapping by extending
``Backbone.PageableCollection``:

.. code-block:: javascript

  var Book = Backbone.Model.extend({});

  var Books = Backbone.PageableCollection.extend({
    model: Book,
    url: "api.mybookstore.com/books",

    // Any `state` or `queryParam` you override in a subclass will be merged with
    // the defaults in `Backbone.PageableCollection` 's prototype.
    state: {

      // You can use 0-based or 1-based indices, the default is 1-based.
      // You can set to 0-based by setting ``firstPage`` to 0.
      firstPage: 0,

      // Set this to the initial page index if different from `firstPage`. Can
      // also be 0-based or 1-based.
      currentPage: 2,

      // Required under server-mode
      totalRecords: 200
    },

    // You can configure the mapping from a `Backbone.PageableCollection#state`
    // key to the query string parameters accepted by your server API.
    queryParams: {

      // `Backbone.PageableCollection#queryParams` converts to ruby's
      // will_paginate keys by default.
      currentPage: "current_page",
      pageSize: "page_size"
    }
  });


You can initialize ``state`` and ``queryParams`` from the constructor too:

.. code-block:: javascript

   var Books = Backbone.PageableCollection.extend({
     model: Book,
     url:"api.mybookstore.com/books"
   });

   var books = new Books([], {

     // All the `state` and `queryParams` key value pairs are merged with
     // the defaults too.
     state: {
       firstPage: 0,
       currentPage: 0
     },

     queryParams: {
       currentPage: "current_page",
       pageSize: "page_size"
     }
   });


Adapting to a Server API
++++++++++++++++++++++++

To adapt to an existing server API that do not use ``will_paginate`` keys, you
can configure the ``queryParams`` object hash to map ``state`` keys to the query
parameters your server will accept. Those query parameters will be in the query
string of the URL used for fetching. You can also put extra items into
``queryParams`` and they will be in the query string as is. Setting ``null`` as
the value of any mapping will remove it from the query string. Finally, the
values in the ``queryParams`` can be either a literal value or a parameter-less
function that returns a value.

This is a listing of the default ``state`` and ``queryParam`` values.

============ ===== ============= ============================
    ``state``                   ``queryParams``
------------------ ------------------------------------------
Attribute    Value Attribute     Value
============ ===== ============= ============================
firstPage    1
lastPage     null
currentPage  null  currentPage   "page"
pageSize     25    pageSize      "per_page"
totalPages   null  totalPages    "total_pages"
totalRecords null  totalRecords  "total_entries"
sortKey      null  sortKey       "sort_by"
order        -1    order         "order"
\                  directions    { "-1": "asc", "1": "desc" }
============ ===== ============= ============================

You can consult the `API documentation
<http://wyuenho.github.com/backbone-pageable/#!/api/Backbone.PageableCollection>`_
for a detailed explaination of these fields.

Fetching Data and Managing States
+++++++++++++++++++++++++++++++++

You can access the pageable collection's internal state by looking at the
``state`` object attached to it. This state object, however, is generally
read-only after initialization. There are various methods to help you manage
this state, you should use them instead of manually modifying it. For the
unusual circumstances where you need to modify the ``state`` object directly, a
sanity check will be performed at the next time you perform any
pagination-specific operations to ensure internal state consistency.

================== ===============================
Method             Use When
================== ===============================
``setPageSize``    Changing the page size
``makeComparator`` Changing the sorting
``switchMode``     Switching between modes
``state``          Need to read the internal state
``get*Page``       Need to go to a different page
================== ===============================

In addition to the above methods, you can also synchronize the state with the
server during a fetch. ``Backbone.PageableCollection`` overrides the default
`Backbone.Collection#parse <http://backbonejs.org/#Collection-parse>`_ method to
support an additional response data structure that contains an object hash of
pagination state. The following is a table of the response data structure
formats ``Backbone.PageableCollection`` accepts.

============= ====================================
Without State With State
============= ====================================
[{}, {}, ...] [{ pagination state }, [{}, {} ...]]
============= ====================================

Bootstrapping
-------------

``Backbone.PageableCollection`` is 100% compatible with ``Backbone.Collection``
's interface, so you can bootstrap the models and supply a comparator to the
constructor just like you are used to:

.. code-block:: javascript

  // Bootstrap with just 1 page of data for server-mode, or all the pages for
  // client-mode.
  var books = new Books([
    { name: "A Tale of Two Cities" },
    { name: "Lord of the Rings" },
    // ...
  ], {
    // Paginate and sort on the client side, default is `server`.
    mode: "client",
    // This will maintain the current page in the order the comparator defined
    // on the client-side, regardless of modes.
    comparator: function (model) { return model.get("name"); }
  });


Pagination
----------

Server-Mode
+++++++++++

``Backbone.Pagination`` defaults to server-mode, which means it only holds one
page of data at a time. All of the ``get*page`` operations are done by
delegating to ``fetch``. They return a ``jqXHR`` in this mode.

.. code-block:: javascript

  books.getFirstPage();
  books.getPreviousPage();
  books.getNextPage();
  books.getLastPage();

  // All the `get*Page` methods under server-mode delegates to `fetch`, so you
  // can attach a callback to the returned `jqXHR` objects' `done` event.
  books.getPage(2).done(function () {
    // do something ...
  });


All of the ``get*Page`` methods accept the same options
`Backbone.Collection#fetch <http://backbonejs.org/#Collection-fetch>`_ accepts
under server-mode.

Infinite-Mode
+++++++++++++

Infinite paging mode is a special case of server mode. You cannot call
``getPage`` directly with a page number under this mode as there is no notion of
a "page number". As a substitute, you have to make use of ``getFirstPage``,
``getPreviousPage``, ``getNextPage``, and ``getLastPage``. For the same reason,
most of the ``state`` attribute is also meaningless under this mode. By default,
``Backbone.PageableCollection`` parses the response headers to find out what the
``first``, ``last``, ``next`` and ``prev`` links are. The parsed links are
available in the ``links`` field.

.. code-block:: javascript

   var Issue = Backbone.Model.extend({});

   var Issues = Backbone.PageableCollection.extend({
     model: Issue,
     url: "https://api.github.com/repos/documentclound/backbone/issues?state=closed",
     mode: "infinite"
   });

   var issues = new Issues();

   issues.getFirstPage().done(function () {
      // do something interesting...
   });

If your server API does not return the links using the ``Link`` header like
`Github <http://developer.github.com/v3/#pagination>`_ does, you can subclass
``Backbone.PageableCollection`` to override the ``parseLinks`` methods to
return a links object.

.. code-block:: javascript

   var FBComment = Backbone.Model.extend({});

   var FBComments = Backbone.PageableCollection.extend({
     model: FBComment,
     url: "https://graph.facebook.com/A_REALLY_LONG_FACEBOOK_OBJECT_ID",
     mode: "infinite",
     // Set the indices to 0-based for Graph API.
     state: {
       firstPage: 0
     },
     queryParams: {
       pageSize: "limit",
       // Setting a parameter mapping value to null removes it from the query string
       currentPage: null,
       // Any extra query string parameters are sent as is, values can be functions,
       // which will be bound to the pageable collection instance temporarily
       // when called.
       offset: function () { return this.state.currentPage * this.state.pageSize; }
     },
     // Return all the comments for this Facebook object
     parse: function (resp) {
       return resp.comments;
     },
     // Facebook's `paging` object is in the exact format
     // `Backbone.PageableCollection` accepts.
     parseLinks: function (resp, xhr) {
       this.state.currentPage++;
       return resp.comments.paging;
     }
   });


Client-Mode
+++++++++++

Client-mode is a very convenient mode for paginating a handful of pages entirely
on the client side without going through the network page-by-page. This mode is
best suited if you only have a small number of pages so sending all of the data
to the client is not too time-consuming.

.. code-block:: javascript

  var book = new Book([
    // Bootstrap all the records for all the pages here
  ], { mode: "client" });


All of the ``get*Page`` methods reset the pageable collection's data to the models
belonging to the current page and return the collection itself instead of a
``jqXHR``.

.. code-block:: javascript

  // You can immediately operate on the collection without waiting for jQuery to
  // call your `done` callback.
  var json = JSON.stringify(books.getLastPage());

  // You can force a fetch in client-mode to get the most updated data if the
  // collection has gone stale.
  books.getFirstPage({ fetch: true }).done(function () {
    // ...
  });


Sorting
-------

There are three ways you can sort a pageable collection. You can sort on the
client-side by either supplying a ``comparator`` like you can do with a plain
``Backbone.Collection``, by setting a ``sortKey`` and ``order`` to ``state``, or
call the convenient method ``makeComparator`` with a ``sortKey`` and ``order``
at any time.

Each sorting method is valid for both server-mode and client-mode
operations. Both modes are capable of sorting on either the current page or all
of the pages.

The following matrices will help you understand all of the different ways you
can sort on a pageable collection.

Server-Mode
+++++++++++

+--------------+-----------------------------------------------+-------------------------------------+
|              |Server-Current                                 |Server-Full                          |
+==============+===============================================+=====================================+
|comparator    | .. code-block:: javascript                    | N/A                                 |
|              |                                               |                                     |
|              |   var books = new Books([], {                 |                                     |
|              |     comparator: function (l, r)  {            |                                     |
|              |       var lv = l.get("name");                 |                                     |
|              |       var rv = r.get("name");                 |                                     |
|              |       if (lv == rv) return 0;                 |                                     |
|              |       else if (lv < rv) return 1;             |                                     |
|              |       else return -1;                         |                                     |
|              |     }                                         |                                     |
|              |   });                                         |                                     |
|              |                                               |                                     |
+--------------+-----------------------------------------------+-------------------------------------+
|state         | N/A                                           | .. code-block:: javascript          |
|              |                                               |                                     |
|              |                                               |   // You need to bootstrap the      |
|              |                                               |   // first page in a globally       |
|              |                                               |   // sorted order                   |
|              |                                               |   var books = new Books([], {       |
|              |                                               |     state: {                        |
|              |                                               |       sortKey: "name",              |
|              |                                               |       order: 1                      |
|              |                                               |     }                               |
|              |                                               |   });                               |
|              |                                               |   // Or perform a fetch using a     |
|              |                                               |   // query string having the sort   |
|              |                                               |   // key and order for a globally   |
|              |                                               |   // sorted page                    |
|              |                                               |   books.getPage(1);                 |
|              |                                               |                                     |
+--------------+-----------------------------------------------+-------------------------------------+
|makeComparator| .. code-block:: javascript                    | N/A                                 |
|              |                                               |                                     |
|              |   var books = new Books([]);                  |                                     |
|              |   var comp = books.makeComparator("name", 1); |                                     |
|              |   books.comparator = comp;                    |                                     |
|              |                                               |                                     |
|              |                                               |                                     |
+--------------+-----------------------------------------------+-------------------------------------+

Client-Mode
+++++++++++

+--------------+------------------------------------+---------------------------------------------+
|              |Client-Current                      |Client-Full                                  |
+==============+====================================+=============================================+
|comparator    | Same as Server-Current. Set        | .. code-block:: javascript                  |
|              | ``mode`` to ``"client"``.          |                                             |
|              |                                    |   var books = new Books([], {               |
|              |                                    |     comparator: function (l, r) {           |
|              |                                    |       var lv = l.get("name");               |
|              |                                    |       var rv = r.get("name");               |
|              |                                    |       if (lv == rv) return 0;               |
|              |                                    |       else if (lv < rv) return 1;           |
|              |                                    |       else return -1;                       |
|              |                                    |     },                                      |
|              |                                    |     mode: "client",                         |
|              |                                    |     full: true                              |
|              |                                    |   });                                       |
|              |                                    |                                             |
+--------------+------------------------------------+---------------------------------------------+
|state         | Same as Server-Full. Set           | .. code-block:: javascript                  |
|              | ``mode`` to ``"client"``.          |                                             |
|              |                                    |   var books = new Books([], {               |
|              |                                    |     state: {                                |
|              |                                    |       sortKey: "name",                      |
|              |                                    |       order: 1                              |
|              |                                    |     },                                      |
|              |                                    |     mode: "client",                         |
|              |                                    |     full: true                              |
|              |                                    |   };                                        |
|              |                                    |                                             |
+--------------+------------------------------------+---------------------------------------------+
|makeComparator| Same as Server-Current. Set        | .. code-block:: javascript                  |
|              | ``mode`` to ``"client"``.          |                                             |
|              |                                    |   var books = new Books([], {               |
|              |                                    |     mode: "client",                         |
|              |                                    |     full: true                              |
|              |                                    |   });                                       |
|              |                                    |   var comp = books.makeComparator("name");  |
|              |                                    |   books.fullCollection.comparator = comp;   |
|              |                                    |                                             |
+--------------+------------------------------------+---------------------------------------------+

Manipulation
------------

This is one of the areas where ``Backbone.PageableCollection`` truely shines. A
``Backbone.PageableCollection`` instance not only can do everything a plain
``Backbone.Collection`` is can for the current page, in client-mode, it can also
synchronize changes and events across all of the pages. For example, you can add
or remove a model from either a ``Backbone.PageableCollection`` instance, which
is holding the current page, or the
``Backbone.PageableCollection#fullCollection`` collection, which is a plain
``Backbone.Collection`` holding the models for all of the pages, and the pages
will all update themselves to maintain within a page size. Any additions,
removals, resets, model attribute changes and synchronization actions are
communicated between all the pages throught the two collections.

.. code-block:: javascript

   // The books collection is initialized to start at the first page.
   var books = new Books([
     // bootstrap with all of the models for all of the pages here
   ], {
     mode: "client"
   });

   // A book is added to the end of the current page, which will overflow to the
   // next page and trigger an `add` event on `fullCollection`.
   books.push({ name: "The Great Gatsby"});

   books.fullCollection.at(books.state.currentPage - 1 * books.state.pageSize).get("name");
   >>> "The Great Gatsby"

   // Add a new book to the beginning of the first page.
   books.fullCollection.unshift({ name: "Oliver Twist" });
   books.at(0).get("name");
   >>> "Oliver Twist"

API Reference
-------------

See `here <http://wyuenho.github.com/backbone-pageable/>`_.

FAQ
---

#. Why another paginator?

   This project was born out of the needs for a backing model for
   `Backgrid.Paginator <http://wyuenho.github.com/backgrid/#api-paginator>`_ -
   an extension for the `Backgrid.js <http://wyuenho.github.com/backgrid/>`_
   project. The project needed a smart and intuitive model that is
   well-documented and well-tested to manage the paginator view. Upon examining
   the popular project `Backbone.Paginator
   <https://github.com/addyosmani/backbone.paginator/>`_, the author has
   concluded that it does not satisfy the above requirements. Furthermore, the
   progress of the the project is too slow. The author hopes to reinvent a
   better wheel that is better suited and supported for `Backgrid.js
   <http://wyuenho.github.com/backgrid/>`_.

#. Which package managers does backbone-pageable support?

   bower, CommonJS and AMD as of 0.9.0.

#. Why doesn't backbone-pageable support filtering?

   Wheels should be reinvented only when they are crooked. backbone-pageable aims
   to do one thing only and does it well, which is pagination and sorting. Besides,
   since Backbone.PageableCollection is 100% compatible with Backbone.Collection,
   you can do filtering fairly easily with Backbone's built-in support for
   Underscore.js methods.

Change Log
----------

0.9.13
  Bugs Fixed
    - ``pageSize`` cannot be larger than ``totalRecords``.
    - Off by 1 problem when shifting a model to the current page after removing
      from ``fullCollection``.
    - ``RangeError`` when removing the last element from the last page when
      ``totalPages`` is > 1.

0.9.12
  Enhancements
    - Switching modes now resets the states by default.
    - Infinite mode now updates ``currentPage`` as well.
  Bugs Fixed
    - ``state`` counters will now update automatically under client mode.

0.9.11
  Changed
    - ``links.first`` and ``links.next`` is now initialized to ``url`` for
      infinite mode. Calling ``fetch`` in infinite mode is now equivalent to
      calling ``getNextPage``.

0.9.10
  Bugs Fixed
    - The initial call to ``getFirstPage`` will no longer fail under
      infinite-mode and will now default to fetch from the collection's ``url``.
    - Function values in ``queryParams`` now has ``this`` bound to the
      collection instance when called.

0.9.9
  Changed
    - ``switchMode`` now accepts a ``mode`` as the first parameter.
    - ``state.isClientMode`` is removed. There is now a new
      ``Backbone.PageableCollection#mode`` attribute for this purpose.
    - ``queryParams.totalRecords`` now maps to ``"total_entries"``.
    - ``queryParams.directions`` now maps to ``{"-1": "asc", "1": "desc"}``.

  Enhancements
    - Support extra ``queryParam`` parameters and function values.
    - Infinite paging.

0.9.2
  This release is tested against Backbone.js 0.9.2 and 0.9.9.

  Enhancements
    - ``currentPage`` defaults to ``firstPage``.

0.9.1
  Bugs Fixed
    - Instantiating a ``PageableCollection`` in client-mode without giving it
      any models no longer throws errors.
  Enhancements
    - Overriding ``state`` and ``queryParams`` in a subclass's prototype now
      merge with the defaults in ``Backbone.PageableCollection.prototype``.
    - fullCollection now respect the parent's prototype.

0.9.0
  Initial release


Legal
-----

Copyright (c) 2013 Jimmy Yuen Ho Wong

Permission is hereby granted, free of charge, to any person obtaining a copy of
this software and associated documentation files (the "Software"), to deal in
the Software without restriction, including without limitation the rights to
use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of
the Software, and to permit persons to whom the Software is furnished to do so,
subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS
FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR
COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER
IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN
CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

.. |travis-status| image:: https://travis-ci.org/wyuenho/backbone-pageable.png
.. _travis-status: https://travis-ci.org/wyuenho/backbone-pageable
