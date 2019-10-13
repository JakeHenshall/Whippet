/*jslint browser: true*/
//Revision: 2018.02.13

var whippet;
whippet = {
    menu: {
        init: function () {
            "use strict";
            var menuItem;

            menuItem = document.getElementById("wp-admin-bar-whippet");
            if (menuItem) {
                menuItem.addEventListener("click", function () {
                    var panel = document.getElementById("whippet");

                    if (panel) {
                        if (panel.style.display === "none") {
                            panel.style.display = "block";
                        } else {
                            panel.style.display = "none";
                        }
                    }
                });
            }
        }
    },
    UI: {
        init: function () {
            "use strict";
            var elements, submitButton;

            elements = document.querySelectorAll("#whippet input[type=checkbox]");
            Array.prototype.forEach.call(elements, function (el) {
                el.addEventListener("change", function () {
                    document.whippetChanged = true;
                });
            });

            elements = document.querySelectorAll("#whippet select");
            Array.prototype.forEach.call(elements, function (el) {
                el.addEventListener("change", function () {

                    //look for wrappers
                    var tr;
                    if (this.parentNode.tagName.toLowerCase() == 'td') {
                        tr = this.parentNode.parentNode;
                    } else {
                        tr = this.parentNode.parentNode.parentNode; //probably wrapper around select
                    }
                    var sectionCond = tr.querySelector(".g-cond");
                    var sectionExcp = tr.querySelector(".g-excp");
                    var sectionRegex = tr.querySelector(".g-regex");
                    var checkedRadio = tr.querySelector(".g-cond input[type=radio]:checked");

                    if (this.value == 'e') {
                        /**
                         * State = Enable
                         */

                        //ukrywanie sekcji "Where" i "Exceptions"
                        whippet.helper.addClass("g-disabled", sectionCond);
                        whippet.helper.addClass("g-disabled", sectionExcp);
                        if (sectionRegex) {
                            whippet.helper.addClass("g-disabled", sectionRegex);
                        }

                        if (checkedRadio) {
                            //odznaczanie stanu "Where"
                            checkedRadio.checked = false;

                            //odznaczanie "Exceptions"
                            var tr2 = this.parentNode.parentNode.parentNode;
                            var section = tr2.querySelector(".g-excp");
                            whippet.helper.clearSelected(section);
                        }
                    } else {
                        /**
                         * State = Disable
                         */

                        //pokazywanie sekcji "where"
                        whippet.helper.removeClass("g-disabled", sectionCond);

                        //jeśli zaznaczono "Everywhere" pokaż tą sekcję
                        if (checkedRadio && checkedRadio.value == 'everywhere') {

                            if (sectionRegex) {
                                whippet.helper.addClass("g-disabled", sectionRegex);
                            }
                            whippet.helper.removeClass("g-disabled", sectionExcp);
                        } else if (checkedRadio && checkedRadio.value == 'regex') {
                            whippet.helper.addClass("g-disabled", sectionExcp);

                            if (sectionRegex) {
                                whippet.helper.removeClass("g-disabled", sectionRegex);
                            }
                        }
                    }

                    document.whippetChanged = true;
                });
            });

            elements = document.querySelectorAll("#whippet .g-cond input[type=radio]");
            Array.prototype.forEach.call(elements, function (el) {
                el.addEventListener("change", function () {

                    var tr = this.parentNode.parentNode.parentNode;
                    var sectionExcp = tr.querySelector(".g-excp");
                    var sectionRegex = tr.querySelector(".g-regex");

                    if (this.value == 'here') {
                        whippet.helper.addClass("g-disabled", sectionExcp);
                        if (sectionRegex) {
                            whippet.helper.addClass("g-disabled", sectionRegex);
                        }
                        whippet.helper.clearSelected(sectionExcp);
                        if (sectionRegex) {
                            whippet.helper.clearSelected(sectionRegex);
                        }
                    } else if (this.value == 'everywhere') {
                        if (sectionRegex) {
                            whippet.helper.addClass("g-disabled", sectionRegex);
                        }
                        whippet.helper.removeClass("g-disabled", sectionExcp);
                    } else if (this.value == 'regex') {
                        whippet.helper.addClass("g-disabled", sectionExcp);
                        if (sectionRegex) {
                            whippet.helper.removeClass("g-disabled", sectionRegex);
                        }
                    }

                    document.whippetChanged = true;
                });
            });

            submitButton = document.getElementById("submit-whippet");
            if (submitButton) {
                submitButton.addEventListener("click", function () {
                    document.whippetChanged = false;
                });
            }
        },
        protection: function () {
            "use strict";
            window.addEventListener("beforeunload", function (e) {
                if (document.whippetChanged) {
                    var confirmationMessage = "It looks like you have been editing configuration and tried to leave page without saving. Press cancel to stay on page.";
                    (e || window.event).returnValue = confirmationMessage;
                    return confirmationMessage;
                }
            });
        }
    },
    helper: {
        addClass: function(className, el) {
            "use strict";
            if (el.classList) {
                el.classList.add(className);
            } else {
                el.className += ' ' + className;
            }
        },
        removeClass: function(className, el) {
            "use strict";
            if (el.classList) {
                el.classList.remove(className);
            } else {
                el.className = el.className.replace(new RegExp('(^|\\b)' + className.split(' ').join('|') + '(\\b|$)', 'gi'), ' ');
            }
        },
        clearSelected: function(el) {
            "use strict";
            var elements = el.querySelectorAll("input[type=checkbox]");
            Array.prototype.forEach.call(elements, function (input) {
                input.checked = false;
            });

            var elements = el.querySelectorAll("textarea");
            Array.prototype.forEach.call(elements, function (input) {
                input.value = "";
            });
        }
    },
    ready: function (fn) {
        "use strict";
        if (document.readyState !== "loading") {
            fn();
        } else {
            document.addEventListener("DOMContentLoaded", fn);
        }
    },
    init: function () {
        "use strict";
        whippet.ready(whippet.menu.init);
        whippet.ready(whippet.UI.init);
        whippet.ready(whippet.UI.protection);
    }
};

setTimeout(function(){ whippet.init(); }, 100);
