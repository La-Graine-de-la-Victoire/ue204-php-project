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