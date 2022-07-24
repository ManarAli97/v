 <br>
 <br>
 <br>

<script src="/v/s/alertify.min.js"></script>
<script src="/v/s/aos.js?60"></script>
<script src="/v/s/chosen.min.js" ></script>
<script>
AOS.init();
</script>
<script type="text/javascript">
$(".chosen-select").chosen();
</script>
<script type="text/javascript">
alertify.defaults = {
      // dialogs defaults
      autoReset:true,
      basic:false,
      closable:true,
      closableByDimmer:true,
      invokeOnCloseOff:false,
      frameless:false,
      defaultFocusOff:false,
      maintainFocus:true, // <== global default not per instance, applies to all dialogs
      maximizable:true,
      modal:true,
      movable:true,
      moveBounded:false,
      overflow:true,
      padding: true,
      pinnable:true,
      pinned:true,
      preventBodyShift:false, // <== global default not per instance, applies to all dialogs
      resizable:true,
      startMaximized:false,
      transition:'fade',
      tabbable:'button:not(:disabled):not(.ajs-reset),[href]:not(:disabled):not(.ajs-reset),input:not(:disabled):not(.ajs-reset),select:not(:disabled):not(.ajs-reset),textarea:not(:disabled):not(.ajs-reset),[tabindex]:not([tabindex^="-"]):not(:disabled):not(.ajs-reset)',  // <== global default not per instance, applies to all dialogs

      // notifier defaults
      notifier:{
      // auto-dismiss wait time (in seconds)
          delay:5,
      // default position
          position:'bottom-right',
      // adds a close button to notifier messages
          closeButton: false,
      // provides the ability to rename notifier classes
          classes : {
              base: 'alertify-notifier',
              prefix:'ajs-',
              message: 'ajs-message',
              top: 'ajs-top',
              right: 'ajs-right',
              bottom: 'ajs-bottom',
              left: 'ajs-left',
              center: 'ajs-center',
              visible: 'ajs-visible',
              hidden: 'ajs-hidden',
              close: 'ajs-close'
          }
      },

      // language resources
      glossary:{
          // dialogs default title
          title:'شركة الاماني',
          // ok button text
          ok: 'موافق',
          // cancel button text
          cancel: 'إلغاء'
      },

      // theme settings
      theme:{
          // class name attached to prompt dialog input textbox.
          input:'ajs-input',
          // class name attached to ok button
          ok:'ajs-ok',
          // class name attached to cancel button
          cancel:'ajs-cancel'
      },
      // global hooks
      hooks:{
          // invoked before initializing any dialog
          preinit:function(instance){},
          // invoked after initializing any dialog
          postinit:function(instance){},
      },
  };
</script>
<script type="text/javascript">

$(document).ready(function() {


  $(document).on('click','.confirm-a',function(){
    var target = $(this).attr('data-target');

    alertify.confirm(
      'تأكيد العملية',
       'هل تريد المتابعة؟',
        function(){
          location.replace(target);
           // alertify.success('Ok')
         }
        , function(){
           // alertify.error('Cancel')
         }
       );
  });
  // *-*-*-*-*-*-*-*-*-*
  // *-*-*-*-*-*-*-*-*-*
  $(document).on('click','.confirm-submit',function(){
    var target = $(this).attr('data-target');
    alertify.confirm(
      'تأكيد العملية',
       'هل تريد المتابعة؟',
        function(){

            if($('.'+target)[0].checkValidity()) {
              $('.'+target).submit();
            }else {
             alertify.error('الرجاء اكمال جميع الحقول')
            }
           // alertify.success('Ok')
         }
        , function(){
           // alertify.error('Cancel')
         }
       );
  });
  // *-*-*-*-*-*-*-*-*-*
  // *-*-*-*-*-*-*-*-*-*
  $(document).on('click','.confirm-request',function(){
    var target = $(this).attr('data-target');


    alertify.prompt( ' تأكيد العملية',
     'ادخل رقم فاتورة كريستال',
     ''
     , function(evt, value) {
       $('.crystal_num').val(value);
       if($('.'+target)[0].checkValidity()) {
         $('.'+target).submit();
       }else {
        alertify.error('الرجاء اكمال جميع الحقول')
       }
        // alertify.success('You entered: ' + value)
       }
     , function() {  });




  });
  // *-*-*-*-*-*-*-*-*-*
  // *-*-*-*-*-*-*-*-*-*


//-*-*-*-*-*-*-*-*-*-
function getCustWait(html) {
  // var html = $('.cust-wait');
  var y = $(html).attr('y');
  var m = $(html).attr('m'); m = parseInt(m-1);
  var d = $(html).attr('d'); d = parseInt(d);
  var h = $(html).attr('h');
  var min = $(html).attr('min'); //alert(m);
  a = new Date(y,m,d,h,min);
  // a = new Date(2019, 6, 11, 17, 35);
  var b = new Date();
    const units = [{size: 60*60*24, name: ' يوم'  },
                   {size: 60*60,    name: ' ساعة'  },
                   {size: 60,       name: 'دقيقة '},
                   {size: 1,        name: 'ثانية'}];
    const result = (b.getTime() - a.getTime()) / 1000;
    const unit = units.find( unit => result >= unit.size );
    var final =  Math.floor(result / unit.size) + unit.name;

    $(html).html(' منذ ' + final);
}

});

</script>
<script type="text/javascript">
function copy(copyText) {
  let input = document.createElement('input');
  input.setAttribute('type', 'text');
  input.value = copyText;
  alert(copyText);
  document.body.appendChild(input);
  input.select();
  document.execCommand("copy");
  document.body.removeChild(input)
}
</script>


</body>
</html>
