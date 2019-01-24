//var url = 'http://cuboide.polytechnique.fr/~luiz.bezerra-pinheiro';
//var url =  'http://localhost/financix';
var url = 'http://192.168.43.18/financix';
//var url = 'https://financixserverdata.000webhostapp.com/financix';
//var url = 'http://129.104.206.62/financix';

function getChartCategories_month() {
    var access_token = localStorage.getItem("access_token");
    var month = sessionStorage.getItem('this_month');
    var year = sessionStorage.getItem('this_year');
    $.ajax({
        method: "post",
        url: url + "/get/getchartcategory_month.php",
        data: {
            access_token: access_token,
            month: month,
            year: year
        },
        success: function (chartdata) {
            console.log(chartdata);
            google.charts.load('current', {'packages': ['corechart']});
            google.charts.setOnLoadCallback(drawChart);

            function drawChart() {

                var data = google.visualization.arrayToDataTable(chartdata);

                var options = {
                };

                var chart = new google.visualization.PieChart(document.getElementById('categorychart'));

                chart.draw(data, options);
            }
        },
        error: function (data) {
            console.log(data);
            return false;
        }
    });


}

function getChartSubCategories_month(category_name) {
    var access_token = localStorage.getItem("access_token");
    var month = sessionStorage.getItem('this_month');
    var year = sessionStorage.getItem('this_year');
    $.ajax({
        method: "post",
        url: url + "/get/getchartsubcategory_month.php",
        data: {
            access_token: access_token,
            month: month,
            year: year,
            category_name: category_name
        },
        success: function (chartdata) {
            console.log(chartdata);
            google.charts.load('current', {'packages': ['corechart']});
            google.charts.setOnLoadCallback(drawChart);

            function drawChart() {

                var data = google.visualization.arrayToDataTable(chartdata);

                var options = {
                };

                var chart = new google.visualization.PieChart(document.getElementById('subcategorychart'));

                chart.draw(data, options);
            }
        },
        error: function (data) {
            console.log(data);
            return false;
        }
    });
}

function thisMonth() {
    var d = (new Date()).getDate();
    var m = (new Date()).getMonth();
    var y = (new Date()).getFullYear();
    m += 1;
    sessionStorage.setItem('this_month', parseInt(m));
    sessionStorage.setItem('this_year', parseInt(y));
    sessionStorage.setItem('this_day', parseInt(d));
}

function prevMonth() {
    var m = sessionStorage.getItem('this_month');
    m = parseInt(m);
    m = (m - 1);
    if (m === 0) {
        m = 12;
        var y = sessionStorage.getItem('this_year');
        y = parseInt(y);
        y -= 1;
        sessionStorage.setItem('this_year', y);
    }
    sessionStorage.setItem('this_month', m);
    location.reload();
}

function nextMonth() {
    var m = sessionStorage.getItem('this_month');
    m = parseInt(m);
    m = (m + 1);
    if (m === 13) {
        m = 1;
        var y = sessionStorage.getItem('this_year');
        y = parseInt(y);
        y += 1;
        sessionStorage.setItem('this_year', y);
    }
    sessionStorage.setItem('this_month', m);
    location.reload();
}

function clickSubcategory(mother_name, subcategory_name) {
    if (confirm("Do you want to remove this subcategory?")) {
        removeSubCategory_1(mother_name, subcategory_name);
    }
}

function addCategory() {
    // the entries
    var category_name = $("#category_name").val();
    var access_token = localStorage.getItem("access_token");
    // ajoutez ici les autres champs du formulaire

    $.ajax({
        method: "post",
        url: url + "/add/addcategory.php",
        data: {
            name: category_name,
            access_token: access_token
        },
        success: function (data) {
            console.log(data);
            if (data.success) {
                alert(data.success);
                return true;
            } else {
                alert(data.error);
                return false;
            }
            location.reload();
        },
        error: function (data) {
            console.log(data);
            return false;
        }
    });
}

function addSubCategory() {
    var name_mother = $("#category_name").val();
    var subcategory_name = $("#subcategory_name").val();
    addSubCategory_1(name_mother, subcategory_name);
}

function addSubCategory_1(name_mother, subcategory_name) {
    // the entries
    //var mother_name = $("category_name").val();
    var access_token = localStorage.getItem("access_token");
    // ajoutez ici les autres champs du formulaire

    $.ajax({
        method: "post",
        url: url + "/add/addsubcategory.php",
        data: {
            name: subcategory_name,
            access_token: access_token,
            name_mother: name_mother
        },
        success: function (data) {
            console.log(data);
            if (data.success) {
                alert(data.success);
                return true;
            } else {
                alert(data.error);
                return false;
            }
            location.reload();
        },
        error: function (data) {
            console.log(data);
            return false;
        }
    });
}

function addSubCategory_2(name_mother) {
    var subcategory_name = $("#subcategory_name").val();
    addSubCategory_1(name_mother, subcategory_name);
}

function addTransaction(value, category, subcategory, day, month, year, description, flag, wallet_name) {
    // the entries

    var access_token = localStorage.getItem("access_token");

    //var mother_name = $("category_name").val();
    // ajoutez ici les autres champs du formulaire

    $.ajax({
        method: "post",
        url: url + "/add/addtransaction.php",
        data: {
            value: value,
            category: category,
            subcategory: subcategory,
            this_day: day,
            this_month: month,
            this_year: year,
            description: description,
            wallet_name: wallet_name,
            access_token: access_token,
            flag: flag
        },
        success: function (data) {
            console.log(data);
            if (data.success) {
                alert(data.success);
                return true;
            } else {
                alert(data.error);
                return false;
            }
            location.reload();                                
        },
        error: function (data) {
            console.log(data);
            return false;
        }
    });

}

function addWallet() {
    // the entries
    var wallet_name = $("#wallet_name").val();
    var access_token = localStorage.getItem("access_token");
    var currency = 1;
    // ajoutez ici les autres champs du formulaire

    $.ajax({
        method: "post",
        url: url + "/add/addwallet.php",
        data: {
            wallet_name: wallet_name,
            access_token: access_token,
            currency: currency
        },
        success: function (data) {
            console.log(data);
            if (data.success) {
                alert(data.success);
                return true;
            } else {
                alert(data.error);
                return false;
            }
            location.reload();
        },
        error: function (data) {
            console.log(data);
            return false;
        }
    });

}

function getCalendarDate(day, month, year) {
    sessionStorage.setItem('calendar_day', day);
    sessionStorage.setItem('calendar_month', month);
    sessionStorage.setItem('calendar_year', year);
}

function getCategories() {
    var access_token = localStorage.getItem("access_token");
    $.ajax({
        method: "post",
        url: url + "/get/getcategories.php",
        data: {
            access_token: access_token
        },
        success: function (data) {
            return data;
        },
        error: function (data) {
            console.log(data);
            return false;
        }
    });

}

function getMonthText() {
    var month = sessionStorage.getItem('this_month');
    var text = "";
    switch (month) {

        case "1":
            text += "January ";
            break;

        case "2":
            text += "February ";
            break;

        case "3":
            text += "March ";
            break;

        case "4":
            text += "April ";
            break;

        case "5":
            text += "May ";
            break;

        case "6":
            text += "June ";
            break;

        case "7":
            text += "July ";
            break;

        case "8":
            text += "August ";
            break;

        case "9":
            text += "September ";
            break;

        case "10":
            text += "October ";
            break;

        case "11":
            text += "November ";
            break;

        default:
            text += "December ";
            break;
    }

    text += sessionStorage.getItem('this_year');
    document.getElementById("month_text").innerHTML = text;
}

function getSubCategories() {
    var category_name = $("#category_name").val();
    return getSubCategories_1(category_name);
}

function getSubCategories_1(category_name) {
    var access_token = localStorage.getItem("access_token");
    var result;
    $.ajax({
        method: "post",
        url: url + "/getsubcategories.php",
        data: {
            name: category_name,
            access_token: access_token
        },
        success: function (data) {
            console.log(data);

            if (data[0].subcategories.length !== 0) {
                return data[0];// Object with 2 camps: {"category":"name", subcategories: ["name":"...",...]}
            } else {
                return '{"error" : "This category has no subcategory"}'; //It has no subcategory
            }
        },
        error: function (data) {
            console.log(data);
            return '{"error" : "This category has no subcategory"}';
        }
    });
    return result;
}

function getData_month() {
    var access_token = localStorage.getItem("access_token");
    var month = 11;
    $.ajax({
        method: "post",
        url: url + "/get/getdata_month.php",
        data: {
            access_token: access_token,
            month: month
        },
        success: function (data) {
            console.log(data);

            return data;
        },
        error: function (data) {
            console.log(data);
            return false;
        }
    });

}

function getDay() {
    return sessionStorage.getItem("this_day");
}

function getMonth() {
    return sessionStorage.getItem("this_month");
}

function getYear() {
    return sessionStorage.getItem("this_year");
}

function removeCategory() {
    // the entries
    var category_name = $("#category_name").val();
    removeCategory_1(category_name);
}

function removeCategory_1(category_name) {
    if (confirm("Do you want to remove this category?")) {
        var access_token = localStorage.getItem("access_token");
        // ajoutez ici les autres champs du formulaire

        $.ajax({
            method: "post",
            url: url + "/remove/removecategory.php",
            data: {
                name: category_name,
                access_token: access_token
            },
            success: function (data) {
                console.log(data);
                if (data.success) {
                    alert(data.success);
                    location.reload();
                } else {
                    alert(data.error);
                }
            },
            error: function (data) {
                console.log(data);
            }
        });
    }
}

function removeSubCategory() {
    var category_name = $("#category_name").val();
    var subcategory_name = $("#subcategory_name").val();
    removeSubCategory_1(category_name, subcategory_name);
}
function removeSubCategory_1(category_name, subcategory_name) {
    var access_token = localStorage.getItem("access_token");
    $.ajax({
        method: "post",
        url: url + "/removesubcategory.php",
        data: {
            name: category_name,
            subcategory_name: subcategory_name,
            access_token: access_token
        },
        success: function (data) {
            console.log(data);
            if (data.success) {
                alert(data.success);
                location.reload();
                return true;
            } else {
                alert(data.error);
                return false;
            }
        },
        error: function (data) {
            console.log(data);
            return false;
        }
    });
}

function removeTransaction(id) {
    if (confirm("Do you want to remove this transaction?")) {

        var access_token = localStorage.getItem("access_token");

        $.ajax({
            method: "post",
            url: url + "/remove/removetransaction.php",
            data: {
                id: id,
                access_token: access_token
            },
            success: function (data) {
                console.log(data);
                if (data.success) {
                    location.reload();
                    return true;
                } else {
                    return false;
                }
            },
            error: function (data) {
                console.log(data);
                return false;
            }
        });
    }
}

function clearSubCategories() {
    var category_name = $("#category_name").val();
    clearSubCategories_1(category_name);
}

function clearSubCategories_1(category_name) {
    var access_token = localStorage.getItem("access_token");
    $.ajax({
        method: "post",
        url: url + "/clearsubcategories.php",
        data: {
            name: category_name,
            access_token: access_token
        },
        success: function (data) {
            console.log(data);
        },
        error: function (data) {
            console.log(data);
        }
    });
}

function TotalBalance(amount_dollar, amount_euro, amount_real) {
    var totalbalance = [];
    if (amount_dollar !== 0)
        totalbalance.push({
            "currency": "" + 0 + "",
            "amount": "" + amount_dollar + ""
        }
        );
    totalbalance.push({
        "currency": "" + 1 + "",
        "amount": "" + amount_euro + ""
    }
    );
    if (amount_real !== 0)
        totalbalance.push({
            "currency": "" + 2 + "",
            "amount": "" + amount_real + ""
        }
        );
    return totalbalance;
}



function getvalue() {
    return sessionStorage.getItem("value");
}

function getcategory() {
    return sessionStorage.getItem("choosedcategory");
}

function getsubcategory() {
    return sessionStorage.getItem("choosed_subcategory");
}

function getAccount() {
    return sessionStorage.getItem("choosed_wallet");
}

function getDescription() {
    var e = document.getElementById("description");
    return e.value;
}

function getmother() {
    return sessionStorage.getItem("mother");
}

function getChoosedCategory() {
    return sessionStorage.getItem("category");
}

function StockChoosedCategory() {
    var e = document.getElementById("categorychoosed");
    var nome = e.options[e.selectedIndex].text;
    sessionStorage.setItem('choosedcategory', nome);
}

function StockChoosedCategory_1(nome) {
    sessionStorage.setItem('choosedcategory', nome);
}
function StockChoosedDay() {
    var e = document.getElementById("dayoptions");
    var nome = e.options[e.selectedIndex].text;
    sessionStorage.setItem('this_day', nome);
}
function StockChoosedMonth() {
    var e = document.getElementById("monthchoosed");
    var nome = e.options[e.selectedIndex].text;
    sessionStorage.setItem('this_month', nome);
}
function StockChoosedYear() {
    var e = document.getElementById("yearchoosed");
    var nome = e.options[e.selectedIndex].text;
    sessionStorage.setItem('choosedyear', nome);
}

function StockChoosedWallet() {
    var e = document.getElementById("walletchoosed");
    var nome = e.options[e.selectedIndex].text;
    sessionStorage.setItem('choosed_wallet', nome);
}

function StockChoosedSubCategory() {
    var e = document.getElementById("subcategorychoosed");
    var nome = e.options[e.selectedIndex].text;
    sessionStorage.setItem('choosed_subcategory', nome);
}

function Stockmother(mother) {
    sessionStorage.setItem('mother', mother);
}

function Stockdate(date) {
    sessionStorage.setItem('date', date);
}

function Stockvalue(x) {
    sessionStorage.setItem('value', x);
}

function Stockcategory(category) {
    sessionStorage.setItem('category', category);
}

function Stocksubcategory(subcategory) {
    sessionStorage.setItem('subcategory', subcategory);
}

function showDayOptions() {
    var choosedmonth = sessionStorage.getItem('this_month');
    var this_day = sessionStorage.getItem('this_day');
    $.ajax({
        method: "post",
        url: url + "/get/getdateoptions.php",
        data: {
            this_month: choosedmonth,
            this_day: this_day
        },
        success: function (data) {
            $.get("template/selectday.html", function (templates) {

                var page = $(templates).html();
                page = Mustache.render(page, data);

                $("#dayoptions").html(page);
            }, "html");

        },
        error: function (data) {
            console.log(data);
            return false;
        }
    });
}

/* Set the width of the side navigation to 250px */
function openNav() {
    document.getElementById("mySidenav").style.width = "250px";
}

/* Set the width of the side navigation to 0 */
function closeNav() {
    document.getElementById("mySidenav").style.width = "0";
}

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$(document).ready(function () {

    $(window).on('hashchange', route);


    function route() {

        var page, hash = window.location.hash;
        refresh_token();
        if (localStorage.getItem("access_token") === null && localStorage.getItem("expires") === null && hash !== "#register") { //If we're already logged in
            hash = "#";
        }
        switch (hash) {
            case "#newwallet":

                $.get("template/newwallet.html", function (templates) {

                    var page = $(templates).html();

                    $("#container").html(page);

                }, "html");

                break;

            case "#login":
                var templ = 'login.html';
                refresh_token();
                if (localStorage.getItem("access_token") != null && localStorage.getItem("expires") != null) {
                    templ = 'financixhome.html';
                    hash = "#";
                }
                $.get("template/" + templ, function (templates) {
                    var page = $(templates).html();
                    $("#container").html(page);
                }, "html");
                break;

            case "#register":
                var templ = 'register.html';
                refresh_token();
                if (localStorage.getItem("access_token") != null && localStorage.getItem("expires") != null) { //If we're already logged in
                    templ = 'financixhome.html';
                    hash = "#";
                    logout();
                }
                
                $.get("template/" + templ, function (templates) {
                    var page = $(templates).html();
                    $("#container").html(page);
                }, "html");
                
                break;

            case "#newsubcategory":

                $.get("template/newsubcategory.html", function (templates) {

                    var page = $(templates).html();

                    $("#container").html(page);

                }, "html");

                break;

            case "#newcategory":

                $.get("template/newcategory.html", function (templates) {

                    var page = $(templates).html();

                    $("#container").html(page);

                }, "html");

                break;

            case "#data":

                var month = sessionStorage.getItem("this_month");
                var access_token = localStorage.getItem("access_token");
                var year = sessionStorage.getItem('this_year');

                $.ajax({
                    method: "post",
                    url: url + "/get/getdata_month.php",
                    data: {
                        month: month,
                        access_token: access_token,
                        year: year
                    },
                    success: function (data) {
                        console.log(data);

                        getChartCategories_month();
                        $.get("template/data.html", function (templates) {

                            var page = $(templates).html();
                            page = Mustache.render(page, data);

                            $("#container").html(page);
                        }, "html");
                    },
                    error: function (data) {
                        console.log(data);
                        $.get("template/dataNull.html", function (templates) {

                            var page = $(templates).html();

                            $("#container").html(page);
                        }, "html");
                    }
                });

                break;

            case "#subcategorydata":

                var month = sessionStorage.getItem("this_month");
                var access_token = localStorage.getItem("access_token");
                var year = sessionStorage.getItem('this_year');
                var category_name = sessionStorage.getItem('choosedcategory');

                $.ajax({
                    method: "post",
                    url: url + "/get/getdatasubcategory_month.php",
                    data: {
                        month: month,
                        access_token: access_token,
                        year: year,
                        category_name: category_name
                    },
                    success: function (data) {
                        console.log(data);
                        getChartSubCategories_month(category_name);
                        $.get("template/datasub.html", function (templates) {

                            var page = $(templates).html();
                            page = Mustache.render(page, data);

                            $("#container").html(page);
                        }, "html");
                    },
                    error: function (data) {
                        console.log(data);
                        $.get("template/datasubNull.html", function (templates) {

                            var page = $(templates).html();

                            $("#container").html(page);
                        }, "html");
                    }
                });

                break;

            case "#configurations":

                $.get("template/configurations.html", function (templates) {

                    var page = $(templates).html();

                    $("#container").html(page);

                }, "html");

                break;

            case "#transactions":
                var month = sessionStorage.getItem("this_month");
                var access_token = localStorage.getItem("access_token");
                var year = sessionStorage.getItem('this_year');

                $.ajax({
                    method: "post",
                    url: url + "/get/gethistory_month.php",
                    data: {
                        month: month,
                        access_token: access_token,
                        year: year
                    },
                    success: function (data) {
                        console.log(data);
                        $.get("template/transactions.html", function (templates) {

                            var page = $(templates).html();
                            page = Mustache.render(page, data);

                            $("#container").html(page);
                        }, "html");
                    },
                    error: function (data) {
                        $.get("template/transactionsNull.html", function (templates) {

                            var page = $(templates).html();

                            $("#container").html(page);
                        }, "html");
                    }
                });

                break;

            case "#incomes":
                var access_token = localStorage.getItem("access_token");
                $.ajax({
                    method: "post",
                    url: url+"/get/getcategories.php",
                    data: {
                        access_token: access_token
                    },
                    success: function (categories) {
                        $.get("template/incomes.html", function (templates) {

                            var page = $(templates).html();

                            page = Mustache.render(page, categories);

                            $("#container").html(page);

                        }, "html");
                    },
                    error: function (data) {
                        console.log(data);
                        return false;
                    }
                });

                break;

            case "#incomes_options":

                var access_token = localStorage.getItem("access_token");
                var category = sessionStorage.getItem("choosedcategory");
                var this_day = sessionStorage.getItem("this_day");
                var this_month = sessionStorage.getItem("this_month");
                var this_year = sessionStorage.getItem("this_year");

                $.ajax({
                    method: "post",
                    url: url+"/get/getexpensesoptions.php",
                    data: {
                        access_token: access_token,
                        category_name: category,
                        this_day: this_day,
                        this_month: this_month,
                        this_year: this_year
                    },
                    success: function (data) {
                        $.get("template/incomes_options.html", function (templates) {

                            var page = $(templates).html();

                            page = Mustache.render(page, data);

                            $("#container").html(page);

                        }, "html");
                    },
                    error: function (data) {
                        console.log(data);
                        return false;
                    }
                });

                break;

            case "#expenses":
                var access_token = localStorage.getItem("access_token");
                $.ajax({
                    method: "post",
                    url: url+"/get/getcategories.php",
                    data: {
                        access_token: access_token
                    },
                    success: function (categories) {
                        $.get("template/expenses.html", function (templates) {

                            var page = $(templates).html();

                            page = Mustache.render(page, categories);

                            $("#container").html(page);

                        }, "html");
                    },
                    error: function (data) {
                        console.log(data);
                        return false;
                    }
                });

                break;

            case "#expenses_options":

                var access_token = localStorage.getItem("access_token");
                var category = sessionStorage.getItem("choosedcategory");
                var this_day = sessionStorage.getItem("this_day");
                var this_month = sessionStorage.getItem("this_month");
                var this_year = sessionStorage.getItem("this_year");

                $.ajax({
                    method: "post",
                    url: url+"/get/getexpensesoptions.php",
                    data: {
                        access_token: access_token,
                        category_name: category,
                        this_day: this_day,
                        this_month: this_month,
                        this_year: this_year
                    },
                    success: function (data) {

                        sessionStorage.setItem('choosed_wallet', data.wallets[0].name);

                        $.get("template/expenses_options.html", function (templates) {

                            var page = $(templates).html();

                            page = Mustache.render(page, data);

                            $("#container").html(page);

                        }, "html");
                    },
                    error: function (data) {
                        console.log(data);
                        return false;
                    }
                });

                break;

            case "#categories":
                var access_token = localStorage.getItem("access_token");
                $.ajax({
                    method: "post",
                    url: url+"/get/getcategories.php",
                    data: {
                        access_token: access_token
                    },
                    success: function (categories) {
                        $.get("template/categories.html", function (templates) {

                            var page = $(templates).html();

                            page = Mustache.render(page, categories);

                            $("#container").html(page);

                        }, "html");
                    },
                    error: function (data) {
                        console.log(data);
                        return false;
                    }
                });

                break;



            default:
                refresh_token();
                
                if (localStorage.getItem("access_token") != null && localStorage.getItem("expires") != null) {
                    var access_token = localStorage.getItem("access_token");
                    $.ajax({
                        method: "post",
                        url: url+"/get/getwallets.php",
                        data: {
                            access_token: access_token
                        },
                        success: function (wallets) {
                            $.get("template/financixhome.html", function (templates) {

                                var page = $(templates).html();

                                page = Mustache.render(page, wallets);

                                $("#container").html(page);

                            }, "html");
                        },
                        error: function (data) {
                            console.log(data);
                            return false;
                        }
                    }); 

                }
                $.get("template/login.html", function (templates) {
                    var page = $(templates).html();
                    $("#container").html(page);
                }, "html");
                break;

        }

    }

    route();

});