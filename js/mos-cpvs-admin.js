jQuery(document).ready(function($) {
    $(window).load(function(){
      $('.mos-cpvs-wrapper .tab-con').hide();
      $('.mos-cpvs-wrapper .tab-con.active').show();
    });

    $('.mos-cpvs-wrapper .tab-nav > a').click(function(event) {
      event.preventDefault();
      var id = $(this).data('id');

      set_mos_cpvs_cookie('plugin_active_tab',id,1);
      $('#mos-cpvs-'+id).addClass('active').show();
      $('#mos-cpvs-'+id).siblings('div').removeClass('active').hide();

      $(this).closest('.tab-nav').addClass('active');
      $(this).closest('.tab-nav').siblings().removeClass('active');
    });
});
