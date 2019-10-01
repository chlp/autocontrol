var datetime = {
    month: ['января', 'февраля', 'марта', 'апреля', 'мая', 'июня', 'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря'],
    show_date: function (node) {
        var _this = this;
        setInterval(function () {
            var date = new Date();
            node.innerHTML = [date.getDate(), ' ', _this.month[date.getMonth()], ' ', date.getFullYear()].join('');
        }, 10000);
    },
    show_time: function (node) {
        setInterval(function () {
            if (time_delimeter) {
                time_delimeter_color = '';
                time_delimeter = false;
            }
            else {
                time_delimeter_color = 'color: transparent;';
                time_delimeter = true;
            }
            var date = new Date();
            var minutes = date.getMinutes();
            if (minutes < 10) var minutes2 = '0' + minutes; else minutes2 = '' + minutes;
            node.innerHTML = [date.getHours(), '<span id="time_delimeter" style="' + time_delimeter_color + '">:</span>', minutes2].join('');
        }, 1000);
    }
};

var time_delimeter = false;
var time_delimeter_color = '#000000';

window.onload = function () {
    datetime.show_date(document.getElementById('header_date'));
    datetime.show_time(document.getElementById('header_time'));
};

function mktime() {
    var d = new Date(),
        r = arguments,
        e = ['Hours', 'Minutes', 'Seconds', 'Month', 'Date', 'FullYear'];

    for (var i = 0; i < e.length; i++) {
        if (typeof r[i] === 'undefined') {
            r[i] = d['get' + e[i]]();
            r[i] += (i === 3); // +1 to fix JS months.
        } else {
            r[i] = parseInt(r[i], 10);
            if (isNaN(r[i])) {
                return false;
            }
        }
    }
    r[5] += (r[5] >= 0 ? (r[5] <= 69 ? 2e3 : (r[5] <= 100 ? 1900 : 0)) : 0);

    d.setFullYear(r[5], r[3] - 1, r[4]);

    d.setHours(r[0], r[1], r[2]);
    var return_ = (d.getTime() / 1e3 >> 0) - (d.getTime() < 0);
    return (return_);
}

function goJs(href) {
    window.location.href = "" + href + "";
    return false;
}

function strings2unixtime(D, T) {
    T = T || '0:0';
    d = D.split('.');
    t = T.split(':');
    return mktime(t[0], t[1], 0, d[1], d[0], d[2]);
}

function go_post(t, v, url) {
    var postData = {};
    postData['ajax'] = 1;
    postData[t] = v;
    console.log(postData);
    $.post(url, postData, function (data) {
        goJs(url);
    });
}

function dump(obj) {
    var out = "";
    if (obj && typeof(obj) == "object") {
        for (var i in obj) {
            out += i + ": " + obj[i] + "\n";
        }
    } else {
        out = obj;
    }
    console.log(out);
}