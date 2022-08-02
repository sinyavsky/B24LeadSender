<?php

require("B24LeadSender.php");

$restApiUrl = "https://demo.bitrix24.ru/rest/1/etodemovveditesvoidannye/crm.lead.add.json"; // урл из настроек Б24
$userId = 1; // ID пользователя, который будет ответственным за лид

$leadSender = new \Sinyavsky\B24LeadSender($restApiUrl, $userId);

// теперь заполняем данные по лиду

// имя :D
$leadSender->SetName("Меган Фокс");


// номер телефона и его тип, на момент написания в Б24 есть такие типы:
// WORK, MOBILE, FAX, HOME, PAGER, MAILING, OTHER
// но можем указать и свой тип, Б24 все-равно его примет

$leadSender->AddPhone("+7 111 111-11-11", "WORK"); // рабочий номер

// можем добавить столько номеров, сколько захотим
$leadSender->AddPhone("+7 222 222-22-22", "MOBILE"); // мобильный номер
$leadSender->AddPhone("+7 333 333-33-33"); // можем тип не указывать, по-умолчанию он будет MOBILE
$leadSender->AddPhone("+7 444 444-44-44", "Еще какой-то номер"); // в Б24 телефон так и подпишется: "Еще какой-то номер"


// с емейлом всё аналогично, на момент написания в Б24 есть такие типы:
// WORK, HOME, MAILING, OTHER
// но можем указать и свой тип

$leadSender->AddEmail("megan111@sinyavsky.com", "WORK"); // рабочий емейл

// как и с телефоном: можем добавить сколько угодно адресов
$leadSender->AddEmail("megan222@sinyavsky.com", "HOME"); // домашний емейл
$leadSender->AddEmail("megan333@sinyavsky.com"); // можем тип не указывать, по-умолчанию он будет WORK
$leadSender->AddEmail("megan444@sinyavsky.com", "Какой-то еще"); // в Б24 емейл так и подпишется: "Какой-то еще"


// устанавливаем заголовок лида
$leadSender->SetTitle("Заказ обратного звонка от: Меган Фокс");


// заполняем поле "Комментарий"
$leadSender->SetComments("Есть очень важное дело, срочно перезвоните!");


// устанавливаем значения пользовательских полей
$leadSender->SetUserField("UF_CRM_4104475401715", "Дополнительное текстовое поле"); // текстовое поле
$leadSender->SetUserField("UF_CRM_1101425437375", true); // поле типа да/нет


// в Б24 есть отдельные поля для UTM-меток, можем установить и их
$leadSender->SetUtmSource("primer_utm_source");
$leadSender->SetUtmMedium("primer_utm_medium");
$leadSender->SetUtmCampaign("primer_utm_campaign");
$leadSender->SetUtmContent("primer_utm_content");
$leadSender->SetUtmTerm("primer_utm_term");


// если надо заполнить еще какое-то поле, для которого нет отдельного метода
// то используем метод SetOther, список всех полей можно посмотреть в документации:
// https://dev.1c-bitrix.ru/rest_help/crm/leads/crm_lead_fields.php
$leadSender->SetOther("ADDRESS_COUNTRY", "США"); // например, можем указать страну


// когда все данные заполнили - отправляем лид
if ($leadSender->Send()) {
    echo "<p>Лид успешно отправлен.</p>";
} else { // если лид не отправлен - в GetError() будет текст ошибки
    echo "<p>При отправке лида возникла ошибка: {$leadSender->GetError()}</p>";

    // сразу можем посмотреть, какие данные мы пытались отправить
    // чтобы быстрее найти причину ошибки
    /*
        echo "<pre>";
        print_r($leadSender->GetQueryData());
        echo "</pre>";
    */
}
