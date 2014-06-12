/**
 *
 * @author Egon Firman <egon.firman@gmail.com>
 *
 */
$(function(){
  function proccessPHPJQueryCallback (data) {
    //uncomment line di bawah ini untuk aktifin log
    if (data.log) { 
      console.log(data.log); 
    }
    // another extending phpjquery
    if (data.jsprint) {
      // require jzebra
      try {
        var applet = document.jzebra;
        applet.findPrinter();
        applet.append(data.jsprint);
        applet.append("\n\n\n\n\n\n\n\n\n\n");
        applet.append("\x1bm");
        applet.print();
      } catch (err) {
        alert ("Error\nPrinter bermasalah:\n");
      }
    }
    if (data.alert) { 
      alert(data.alert); 
    }
    if (data.before) {
      for (i=0;i<data.before.length;i++) {
        $(data.before[i].selector).before(data.before[i].msg);
      }
    }
    if (data.after) {
      for (i=0;i<data.after.length;i++) {
        $(data.after[i].selector).after(data.after[i].msg);
      }
    }
    if (data.html) {
      for (i=0;i<data.html.length;i++) {
        $(data.html[i].selector).html(data.html[i].msg);
      }
    }
    if (data.append) {
      for (i=0;i<data.append.length;i++) {
        $(data.append[i].selector).append(data.append[i].msg);
      }
    }
    // this is wierd
    if (data.focus) { 
      $(data.focus).focus(); 
    }
    // must check this because something
    if (data.val) {
      for (i=0;i<data.val.length;i++) {
        $(data.val[i].selector).val(data.val[i].msg);
      }
    }
    if (data.attr) {
      for (i=0;i<data.attr.length;i++) {
        $(data.attr[i].selector).attr(data.attr[i].attr, data.attr[i].msg);
        console.log(data.attr);
      }
    }
    if (data.clone_appendTo) {
      for (i=0;i<data.clone_appendTo.length;i++) {
        $(data.clone_appendTo[i].selector_from).clone().appendTo(data.clone_appendTo[i].selector_to);
      }
    }
    if (data.addClass) {
      for (i=0;i<data.addClass.length;i++) {
        $(data.addClass[i].selector).removeClass(data.removeClass[i].msg);
      }
    }
    if (data.removeClass) {
      for (i=0;i<data.removeClass.length;i++) {
        $(data.removeClass[i].selector).removeClass(data.removeClass[i].msg);
      }
    }
    // extending phpjquery
    if (data.template) {
      // require Hogan or maybe mustache
      for (i=0;i<data.template.length;i++) {
        selector = data.template[i].selector;
        msg      = data.template[i].msg;
        tmpl = Hogan.compile(template[selector]);
        $(selector).html(tmpl.render(msg));
      }
    }
    if (data.jseval) {
      jseval = decodeURIComponent ((data.jseval +'').replace(/\+/g, '%20'))
        eval (jseval);
    }
    if (data.redirect) {
      window.location = data.redirect
    }
    if (data.callback) {
      $.ajax({
        url : data.callback,
        method : 'GET',
        dataType : 'json',
        success : proccessPHPJQueryCallback,
        error    : function (obj,stat) {
          alert (stat + ":" + obj.responseText);
        }
      });
    }
  }


  jQuery.fn.phpjquerycallback = function () {
    return this.each(function () {

      // if its form
      if (this.tagName == 'FORM') {
        this.onsubmit = function(e) {
          $.ajax({ 
            url      : this.getAttribute('action'), 
            data     : $(this).serialize(),
            type     : this.getAttribute('method'),
            dataType : 'json',
            success  : proccessPHPJQueryCallback,
            error    : function (obj,stat) {
              alert (stat + ":" + obj.responseText);
            }
          });
          return false;
        }
      }

      // if its an input
      else if (this.tagName == 'INPUT' || this.tagName == 'TEXTAREA' ) {
        this.onchange = function(e) {
          $.ajax({ 
            url      : this.getAttribute('data-url'), 
            data     : $(this).serialize(),
            dataType : 'json',
            success  : proccessPHPJQueryCallback,
            error    : function (obj,stat) {
              alert (stat + ":" + obj.responseText);
            }
          });
        };
      }
    });
  };

    
  jQuery.processPHPJQueryCallback = proccessPHPJQueryCallback;
});
