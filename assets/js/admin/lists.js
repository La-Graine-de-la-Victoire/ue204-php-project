function usersList() {
   new DataTable('#__usersList', {
      ajax: {
         'url': '/controllers/admin/AdminUsersController.php?getList=1',
         'dataSrc': ''
      },
      'columns': [
         {'data': 'id',},
         {'data': 'lastName',},
         {'data': 'firstName',},
         {'data': 'creationDate',},
         {'data': 'profile',},
      ]
   });
}

function getUserOrders() {
   new DataTable('#__ordersList', {
      ajax: {
         'url': '/controllers/admin/AdminOrdersController.php?getList=1&user=' + $('#__userID').html(),
         'dataSrc': ''
      },
      'columns': [
         {'data': 'quantity',},
         {'data': 'totalPrice',},
         {'data': 'creationDate',},
         {'data': 'closeDate',},
         {'data': 'status',},
         {'data': 'action',},
      ]
   });
}

function getProductsList() {
   new DataTable('#__productsList', {
      ajax: {
         'url': '/controllers/ProductsController.php?getList=1',
         'dataSrc': ''
      },
      'columns': [
         {'data': 'id',},
         {'data': 'name',},
         {'data': 'editor',},
         {'data': 'price',},
         {'data': 'quantity',},
         {'data': 'actions',},
      ]
   });
}

function ordersList(uri = '') {
   const finalURI = '?getList=1'+uri;
   new DataTable('#__ordersList', {
      ajax: {
         'url': '/controllers/admin/AdminOrdersController.php'+finalURI,
         'dataSrc': ''
      },
      'columns': [
         {'data': 'id',},
         {'data': 'quantity',},
         {'data': 'client',},
         {'data': 'closeDate',},
         {'data':'totalPrice',},
         {'data':'status',},
         {'data': 'action',},
      ]
   });
}