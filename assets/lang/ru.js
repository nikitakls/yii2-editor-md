(function () {

    var factory = function (exports) {
        var lang = {
            name: "ru",
            description: "Open source online Markdown editor.",
            tocTitle: "Содержание",
            toolbar: {
                undo: "Отменить (Ctrl+Z)",
                redo: "Повторить (Ctrl+Y)",
                bold: "Жирный",
                del: "Зачеркнутый текст",
                italic: "Курсив",
                quote: "Цитата",
                ucwords: "Первая буква слов в верхнем регистре",
                uppercase: "Конвертировать в верхний регистр",
                lowercase: "Конвертировать в нижний регистр",
                h1: "Заголовок 1",
                h2: "Заголовок 2",
                h3: "Заголовок 3",
                h4: "Заголовок 4",
                h5: "Заголовок 5",
                h6: "Заголовок 6",
                "list-ul": "Маркированный список",
                "list-ol": "Нумернованный список",
                hr: "Горизонтальный разделитель",
                link: "Ссылка",
                "reference-link": "Контекстная ссылка",
                image: "Изображение",
                code: "Код в строке",
                "preformatted-text": "Отфрматированный текст / Блок кода (С отступами)",
                "code-block": "Блок кода (Многоязычный)",
                table: "Таблица",
                datetime: "Дата",
                emoji: "Эмоции",
                "html-entities": "HTML элементы",
                pagebreak: "Разрыв страницы",
                watch: "Не наблюдать",
                unwatch: "Наблюдать",
                preview: "Предпросмотр (Нажмите + ESC exit)",
                fullscreen: "Полный экран (Press ESC exit)",
                clear: "Очистить",
                search: "Искать",
                help: "Справка",
                info: "Информация о " + exports.title
            },
            buttons: {
                enter: "Вставить",
                cancel: "Отменить",
                close: "Закрыть"
            },
            dialog: {
                link: {
                    title: "Ссылка",
                    url: "Адрес",
                    urlTitle: "Заголовок",
                    urlEmpty: "Ошибка: Пожалуйста, заполните адрес ссылки."
                },
                referenceLink: {
                    title: "Reference link",
                    name: "Название",
                    url: "Адрес",
                    urlId: "ID",
                    urlTitle: "Заголовок",
                    nameEmpty: "Ошибка: Название не может быть пустым.",
                    idEmpty: "Ошибка: Пожалуйста, заполните поле ID.",
                    urlEmpty: "Ошибка: Пожалуйста, заполните адерс."
                },
                image: {
                    title: "Изображение",
                    url: "Адрес",
                    link: "Ссылка",
                    alt: "Описание",
                    uploadButton: "Загрузить",
                    imageURLEmpty: "Ошибка: адрес изображения не может быть пустым",
                    uploadFileEmpty: "Ошибка: загружаемое изображение не может быть путым!",
                    formatNotAllowed: "Ошибка: разрешены только следующие форматы файлов:"
                },
                preformattedText: {
                        title: "Форматированный текст / код",
                    emptyAlert: "Ошибка: Пожалуйста, заполните форматированный текст или блок кода"
                },
                codeBlock: {
                    title: "Блок с кодом",
                    selectLabel: "Язык: ",
                    selectDefaultText: "выберите язык...",
                    otherLanguage: "Другие языки",
                    unselectedLanguageAlert: "Ошибка: Пожалуйста, выберите язык.",
                    codeEmptyAlert: "Ошибка: Пожалуйста заполните блок с кодом."
                },
                htmlEntities: {
                    title: "HTML элементы"
                },
                help: {
                    title: "Помощь"
                }
            }
        };

        exports.defaults.lang = lang;
    };

    // CommonJS/Node.js
    if (typeof require === "function" && typeof exports === "object" && typeof module === "object") {
        module.exports = factory;
    }
    else if (typeof define === "function")  // AMD/CMD/Sea.js
    {
        if (define.amd) { // for Require.js

            define(["editormd"], function (editormd) {
                factory(editormd);
            });

        } else { // for Sea.js
            define(function (require) {
                var editormd = require("../editormd");
                factory(editormd);
            });
        }
    }
    else {
        factory(window.editormd);
    }

})();