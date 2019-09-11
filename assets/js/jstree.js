import 'jstree'

$(function() {
  var $tree = $('#tree')
  var newUrl = $tree.data('new')
  var editUrl = $tree.data('edit')
  var deleteUrl = $tree.data('new')
  $tree.jstree({
    core: {
      data: {
        url: function(node) {
          return (node.id === '#')
            ? $tree.data('url')
            : $tree.data('url') + '?parent_id=' + node.id
        }
      }
    },
    plugins: ['contextmenu'],
    contextmenu: {         
      items: function(node) {
        return {
          'product': {
            'icon': require('../img/product.png'),
            'label': '添加产品',
            'action': function (obj) {
              window.location.href = $tree.data('product').replace(':categoryId', node.id)
            }
          },
          'new': {
            'icon': require('../img/new.png'),
            'label': '添加下级',
            'action': function (obj) {
              window.location.href = $tree.data('new').replace(':parentId', node.id)
            }
          },
          'edit': {
            'icon': require('../img/edit.png'),
            'label': '修改',
            'action': function (obj) { 
              window.location.href = $tree.data('edit').replace(':id', node.id)
            }
          },                         
          'delete': {
            'icon': require('../img/delete.png'),
            'label': '删除',
            'action': function (obj) { 
              window.location.href = $tree.data('delete').replace(':id', node.id)
            }
          }
        };
      }
    }
  })
})
