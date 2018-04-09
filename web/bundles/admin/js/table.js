(function () {
  $(document).ready(init);

  function init() {
    var tables = $('table');
    var table1 = tables.first();
    var table2 = tables.eq(1);
    var aside = $('aside');
    var scroll = table2.parent();

    scroll.on('mousewheel', function (e) {
      if (e.originalEvent.wheelDelta < 0) {
        scroll.scrollTop(scroll.scrollTop() + 38);
      } else {
        scroll.scrollTop(scroll.scrollTop() - 38);
      }
      e.preventDefault();
    });

    new ResizeSensor(aside, function () {
      table1.width(table2.width());
    });

    $(window).resize(function () {
      table1.width(table2.width());
    })
  }
}());
