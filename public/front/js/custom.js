// JavaScript Document
$( ".dropdown-menu" ).css('margin-top',0);
$( ".dropdown" )
  .mouseover(function() {
    $( this ).addClass('show').attr('aria-expanded',"true");
    $( this ).find('.dropdown-menu').addClass('show');
  })
  .mouseout(function() {
    $( this ).removeClass('show').attr('aria-expanded',"false");
    $( this ).find('.dropdown-menu').removeClass('show');
  });