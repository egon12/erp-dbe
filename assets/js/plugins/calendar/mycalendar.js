/**
 *
 * Simple calendar creator.
 * requre moment.js
 * @author Egon Firman <egon.firman@gmail.com>
 *
 * todo masukin function yang kalau dipanggil dapat higlight tanggal
 * dan punya link
 * todo month hanya satu kali tampil aja.
 */

$(function () {

  jQuery.fn.calendar = function () {
    return this.each(function () {

      var table = "<table></table>";
      var thead = '<thead><tr><th></th><th style="background-color:#ffcccc;">Sun</th><th>Mon</th><th>Tue</th><th>Wen</th><th>Thu</th><th>Fri</th><th>Sat</th></tr></thead>';
      var tbody = "<tbody></tbody>";

      $tbody = $(tbody);
      $thead = $(thead);
      $table = $(table);
      weekNow = moment().week();
      for (w=-2;w<3;w++) {

        week = moment().week(weekNow  + w);
        tr = $tbody.append('<tr/>');
        tr.append('<td>' + week.format('MMM') + '</td>');
        for (i=0;i<7;i++) {
          date = week.day(i).date()
          if (i == 0) {
            tr.append('<td class="sun">'+ date + '</td>');
          } else if (date == moment().date() && week.month() == moment().month() ) {
            tr.append('<td class="now">'+ date + '</td>');
          } else {
            tr.append('<td>'+ date + '</td>');
          }

          if (date == 1) { 
            console.log(tr)
            //tr.child..html(moment().week(weekNow+w).day(i).format('MMM'));
          }
        };
      };
      $table.append($thead);
      $table.append($tbody);


      // setting style
      $thead.find('th').css('width', '50px');
      $thead.find('th').css('text-align', 'center');
      //$thead.find('th').css('border-style', 'solid');
      //$thead.find('th').css('border-color', '#bbbbbb');
      //$thead.find('th').css('border-width', '2px');
      $thead.find('th').css('padding', '3px');
      $thead.find('th:first').css('width', '80px');

      $tbody.find('td').css('text-align', 'center');
      //$tbody.find('td').css('border-style', 'solid');
      //$tbody.find('td').css('border-color', '#cccccc');
      //$tbody.find('td').css('border-width', '2px');
      $tbody.find('td').css('padding', '3px');

      $tbody.find('td.now').css('background-color', '#ccccff');
      $tbody.find('td.sun').css('background-color', '#ffcccc');
      $table.css('border-width', '1px');
      return $(this).append($table);
    });
  }
});
