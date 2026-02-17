
//Data table
  $(document).ready( function () {

    

    var target = [];

    $('#myTable th').each(function(i){
      var check = $(this).attr('sort');
      if(!check){
        target.push(i);
      }
    });

    $('#myTable').DataTable({
    paging: true,
    searching: true,
    ordering:  true,
    select: true,
    columnDefs: [
        {
            targets: target,
            orderable: false

        },
        // {
        //     targets: [13],
        //     visible: false
        // },
        // {
        //     targets: [14],
        //     type: 'num'
        // }
    ],
    layout: {
        topStart: 'pageLength',
        topEnd: {
            search: {
                text: '_INPUT_',
                placeholder: 'Search ...'
            },
        },         
        bottomStart: 'info',
        bottomEnd: 'paging'
    },
    initComplete: function () {
      $('div.dt-search input').addClass('form-control col-6')
      $('div.dt-length select').addClass('form-control col-md-4').css('order','2')
      $('div.dt-length label').css({'order': '1','padding': '6px 5px 0px 0px'}).html('Per Page: ')
      $('div.dt-length').css('display','flex')
      $('div.dt-search').css({'display':'flex','justify-content': 'end'})
      $('div.dt-search label').append($('.reset-button').html()).css('padding-left', '15px');
      $('div.dt-search label').append($('.add-button').html()).css({'padding-left': '15px','display':'flex'});
      $('.add-button').remove();
    }
} );
  } );
