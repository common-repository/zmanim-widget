<?php
function isJewishLeapYear($year) {
  if ($year % 19 == 0 || $year % 19 == 3 || $year % 19 == 6 ||
      $year % 19 == 8 || $year % 19 == 11 || $year % 19 == 14 ||
      $year % 19 == 17)
    return true;
  else
    return false;
}

function getJewishMonthName($jewishMonth, $jewishYear) {
  $jewishMonthNamesLeap = array("Тишрей", "Хешван", "Кислев", "Тевет",
                                "Шват", "Адар I", "Адар II", "Нисан",
                                "Ияр", "Сиван", "Тамуз", "Ав", "Элул");
  $jewishMonthNamesNonLeap = array("Тишрей", "Хешван", "Кислев", "Тевет",
                                   "Шват", "Адар I", "", "Нисан",
                                   "Ияр", "Сиван", "Тамуз", "Ав", "Элул");
  
  if (isJewishLeapYear($jewishYear))
    return $jewishMonthNamesLeap[$jewishMonth-1];
  else
    return $jewishMonthNamesNonLeap[$jewishMonth-1];
}

function getJewishHoliday($jdCurrent, $isDiaspora, $postponeShushanPurimOnSaturday) {
  $result = array();

  $TISHRI = 1;
  $HESHVAN = 2;
  $KISLEV = 3;
  $TEVET = 4;
  $SHEVAT = 5;
  $ADAR = 6;
  $ADAR_I = 6;
  $ADAR_II = 7;
  $NISAN = 8;
  $IYAR = 9;
  $SIVAN = 10;
  $TAMMUZ = 11;
  $AV = 12;
  $ELUL = 13;

  $SUNDAY = 0;
  $MONDAY = 1;
  $TUESDAY = 2;
  $WEDNESDAY = 3;
  $THURSDAY = 4;
  $FRIDAY = 5;
  $SATURDAY = 6;

  $jewishDate = jdtojewish($jdCurrent);
  list($jewishMonth, $jewishDay, $jewishYear) = split('/', $jewishDate);

  // Holidays in Elul
  if ($jewishDay == 29 && $jewishMonth == $ELUL)
    $result[] = "Вечер Рош hАшана";

  // Holidays in Tishri
  if ($jewishDay == 1 && $jewishMonth == $TISHRI)
    $result[] = "Рош hАшана I";
  if ($jewishDay == 2 && $jewishMonth == $TISHRI)
    $result[] = "Рош hАшана II";
  $jd = jewishtojd($TISHRI, 3, $jewishYear);
  $weekdayNo = jddayofweek($jd, 0);
  if ($weekdayNo == $SATURDAY) { // If the 3 Tishri would fall on Saturday ...
    // ... postpone Tzom Gedaliah to Sunday
    if ($jewishDay == 4 && $jewishMonth == $TISHRI)
      $result[] = "Пост Гедальи";
  } else {
    if ($jewishDay == 3 && $jewishMonth == $TISHRI)
      $result[] = "Пост Гедальи";
  }
  if ($jewishDay == 9 && $jewishMonth == $TISHRI)
    $result[] = "Вечер Йом Кипура";
  if ($jewishDay == 10 && $jewishMonth == $TISHRI)
    $result[] = "Йом Кипура";
  if ($jewishDay == 14 && $jewishMonth == $TISHRI)
    $result[] = "Вечер Суккота";
  if ($jewishDay == 15 && $jewishMonth == $TISHRI)
    $result[] = "Суккот I";
  if ($jewishDay == 16 && $jewishMonth == $TISHRI && $isDiaspora)
    $result[] = "Суккот II";
  if ($isDiaspora) {
    if ($jewishDay >= 17 && $jewishDay <= 20 && $jewishMonth == $TISHRI)
      $result[] = "Холь Амоэд Суккота";
  } else {
    if ($jewishDay >= 16 && $jewishDay <= 20 && $jewishMonth == $TISHRI)
      $result[] = "Холь Амоэд Суккота";
  }
  if ($jewishDay == 21 && $jewishMonth == $TISHRI)
    $result[] = "Хошана Рабба";
  if ($isDiaspora) {
    if ($jewishDay == 22 && $jewishMonth == $TISHRI)
      $result[] = "Шмини Ацерет";
    if ($jewishDay == 23 && $jewishMonth == $TISHRI)
      $result[] = "Симхат Тора";
    if ($jewishDay == 24 && $jewishMonth == $TISHRI)
      $result[] = "Исру Хаг";
  } else {
    if ($jewishDay == 22 && $jewishMonth == $TISHRI)
      $result[] = "Шмини Ацерет/Симхат Тора";
    if ($jewishDay == 23 && $jewishMonth == $TISHRI)
      $result[] = "Исру Хаг";
  }

  // Holidays in Kislev/Tevet
  $hanukkahStart = jewishtojd($KISLEV, 25, $jewishYear);
  $hanukkahNo = (int) ($jdCurrent-$hanukkahStart+1);
  if ($hanukkahNo == 1) $result[] = "Ханука I";
  if ($hanukkahNo == 2) $result[] = "Ханука II";
  if ($hanukkahNo == 3) $result[] = "Ханука III";
  if ($hanukkahNo == 4) $result[] = "Ханука IV";
  if ($hanukkahNo == 5) $result[] = "Ханука V";
  if ($hanukkahNo == 6) $result[] = "Ханука VI";
  if ($hanukkahNo == 7) $result[] = "Ханука VII";
  if ($hanukkahNo == 8) $result[] = "Ханука VIII";

  // Holidays in Tevet
  $jd = jewishtojd($TEVET, 10, $jewishYear);
  $weekdayNo = jddayofweek($jd, 0);
  if ($weekdayNo == $SATURDAY) { // If the 10 Tevet would fall on Saturday ...
    // ... postpone Tzom Tevet to Sunday
    if ($jewishDay == 11 && $jewishMonth == $TEVET)
      $result[] = "Пост Тевета";
  } else {
    if ($jewishDay == 10 && $jewishMonth == $TEVET)
      $result[] = "Пост Тевета";
  }

  // Holidays in Shevat
  if ($jewishDay == 15 && $jewishMonth == $SHEVAT)
    $result[] = "Ту бэШват";

  // Holidays in Adar I
  if (isJewishLeapYear($jewishYear) && $jewishDay == 14 && $jewishMonth == $ADAR_I)
    $result[] = "Пурим Катан";
  if (isJewishLeapYear($jewishYear) && $jewishDay == 15 && $jewishMonth == $ADAR_I)
    $result[] = "Шушан Пурим Катан";

  // Holidays in Adar or Adar II
  if (isJewishLeapYear($jewishYear))
    $purimMonth = $ADAR_II;
  else
    $purimMonth = $ADAR;
  $jd = jewishtojd($purimMonth, 13, $jewishYear);
  $weekdayNo = jddayofweek($jd, 0);
  if ($weekdayNo == $SATURDAY) { // If the 13 Adar or Adar II would fall on Saturday ...
    // ... move Ta'anit Esther to the preceding Thursday
    if ($jewishDay == 11 && $jewishMonth == $purimMonth)
      $result[] = "Пост Эстер";
  } else {
    if ($jewishDay == 13 && $jewishMonth == $purimMonth)
      $result[] = "Пост Эстер";
  }
  if ($jewishDay == 14 && $jewishMonth == $purimMonth)
    $result[] = "Пурим";
  if ($postponeShushanPurimOnSaturday) {
    $jd = jewishtojd($purimMonth, 15, $jewishYear);
    $weekdayNo = jddayofweek($jd, 0);
    if ($weekdayNo == $SATURDAY) { // If the 15 Adar or Adar II would fall on Saturday ...
      // ... postpone Shushan Purim to Sunday
      if ($jewishDay == 16 && $jewishMonth == $purimMonth)
        $result[] = "Шушан Пурим";
    } else {
      if ($jewishDay == 15 && $jewishMonth == $purimMonth)
        $result[] = "Шушан Пурим";
    }
  } else {
    if ($jewishDay == 15 && $jewishMonth == $purimMonth)
      $result[] = "Шушан Пурим";
  }

  // Holidays in Nisan
  $shabbatHagadolDay = 14;
  $jd = jewishtojd($NISAN, $shabbatHagadolDay, $jewishYear);
  while (jddayofweek($jd, 0) != $SATURDAY) {
    $jd--;
    $shabbatHagadolDay--;
  }
  if ($jewishDay == $shabbatHagadolDay && $jewishMonth == $NISAN)
    $result[] = "Шабат hаГадоль";
  if ($jewishDay == 14 && $jewishMonth == $NISAN)
    $result[] = "Вечер Песаха";
  if ($jewishDay == 15 && $jewishMonth == $NISAN)
    $result[] = "Песах I";
  if ($jewishDay == 16 && $jewishMonth == $NISAN && $isDiaspora)
    $result[] = "Песах II";
  if ($isDiaspora) {
    if ($jewishDay >= 17 && $jewishDay <= 20 && $jewishMonth == $NISAN)
      $result[] = "Холь Амоэд Песах";
  } else {
    if ($jewishDay >= 16 && $jewishDay <= 20 && $jewishMonth == $NISAN)
      $result[] = "Холь Амоэд Песах";
  }
  if ($jewishDay == 21 && $jewishMonth == $NISAN)
    $result[] = "Песах VII";
  if ($jewishDay == 22 && $jewishMonth == $NISAN && $isDiaspora)
    $result[] = "Песах VIII";
  if ($isDiaspora) {
    if ($jewishDay == 23 && $jewishMonth == $NISAN)
      $result[] = "Исру Хаг";
  } else {
    if ($jewishDay == 22 && $jewishMonth == $NISAN)
      $result[] = "Исру Хаг";
  }

  $jd = jewishtojd($NISAN, 27, $jewishYear);
  $weekdayNo = jddayofweek($jd, 0);
  if ($weekdayNo == $FRIDAY) { // If the 27 Nisan would fall on Friday ...
    // ... then Yom Hashoah falls on Thursday
    if ($jewishDay == 26 && $jewishMonth == $NISAN)
      $result[] = "День Катастрофы";
  } else {
    if ($jewishYear >= 5757) { // Since 1997 (5757) ...
      if ($weekdayNo == $SUNDAY) { // If the 27 Nisan would fall on Friday ...
        // ... then Yom Hashoah falls on Thursday
        if ($jewishDay == 28 && $jewishMonth == $NISAN)
          $result[] = "День Катастрофы";
      } else {
        if ($jewishDay == 27 && $jewishMonth == $NISAN)
          $result[] = "День Катастрофы";
      }
    } else {
      if ($jewishDay == 27 && $jewishMonth == $NISAN)
        $result[] = "День Катастрофы";
    }
  }

  // Holidays in Iyar

  $jd = jewishtojd($IYAR, 4, $jewishYear);
  $weekdayNo = jddayofweek($jd, 0);

  // If the 4 Iyar would fall on Friday or Thursday ...
  // ... then Yom Hazikaron falls on Wednesday and Yom Ha'Atzmaut on Thursday
  if ($weekdayNo == $FRIDAY) {
    if ($jewishDay == 2 && $jewishMonth == $IYAR)
      $result[] = "День Поминовения";
    if ($jewishDay == 3 && $jewishMonth == $IYAR)
      $result[] = "День Независимости Израиля";
  } else {
    if ($weekdayNo == $THURSDAY) {
      if ($jewishDay == 3 && $jewishMonth == $IYAR)
        $result[] = "День Поминовения";
      if ($jewishDay == 4 && $jewishMonth == $IYAR)
        $result[] = "День Независимости Израиля";
    } else {
      if ($jewishYear >= 5764) { // Since 2004 (5764) ...
        if ($weekdayNo == $SUNDAY) { // If the 4 Iyar would fall on Sunday ...
          // ... then Yom Hazicaron falls on Monday
          if ($jewishDay == 5 && $jewishMonth == $IYAR)
            $result[] = "День Поминовения";
          if ($jewishDay == 6 && $jewishMonth == $IYAR)
            $result[] = "День Независимости Израиля";
        } else {
          if ($jewishDay == 4 && $jewishMonth == $IYAR)
            $result[] = "День Поминовения";
          if ($jewishDay == 5 && $jewishMonth == $IYAR)
            $result[] = "День Независимости Израиля";
        }
      } else {
        if ($jewishDay == 4 && $jewishMonth == $IYAR)
          $result[] = "День Поминовения";
        if ($jewishDay == 5 && $jewishMonth == $IYAR)
          $result[] = "День Независимости Израиля";
      }
    }
  }

  if ($jewishDay == 14 && $jewishMonth == $IYAR)
    $result[] = "Второй Песах";
  if ($jewishDay == 18 && $jewishMonth == $IYAR)
    $result[] = "Лаг баОмер";
  if ($jewishDay == 28 && $jewishMonth == $IYAR)
    $result[] = "День Иерусалима";

  // Holidays in Sivan
  if ($jewishDay == 5 && $jewishMonth == $SIVAN)
    $result[] = "Вечер Шавуота";
  if ($jewishDay == 6 && $jewishMonth == $SIVAN)
    $result[] = "Шавуот I";
  if ($jewishDay == 7 && $jewishMonth == $SIVAN && $isDiaspora)
    $result[] = "Шавуот II";
  if ($isDiaspora) {
    if ($jewishDay == 8 && $jewishMonth == $SIVAN)
      $result[] = "Исру Хаг";
  } else {
    if ($jewishDay == 7 && $jewishMonth == $SIVAN)
      $result[] = "Исру Хаг";
  }

  // Holidays in Tammuz
  $jd = jewishtojd($TAMMUZ, 17, $jewishYear);
  $weekdayNo = jddayofweek($jd, 0);
  if ($weekdayNo == $SATURDAY) { // If the 17 Tammuz would fall on Saturday ...
    // ... postpone Tzom Tammuz to Sunday
    if ($jewishDay == 18 && $jewishMonth == $TAMMUZ)
      $result[] = "Пост Тамуза";
  } else {
    if ($jewishDay == 17 && $jewishMonth == $TAMMUZ)
      $result[] = "Пост Тамуза";
  }
  
  // Holidays in Av
  $jd = jewishtojd($AV, 9, $jewishYear);
  $weekdayNo = jddayofweek($jd, 0);
  if ($weekdayNo == $SATURDAY) { // If the 9 Av would fall on Saturday ...
    // ... postpone Tisha B'Av to Sunday
    if ($jewishDay == 10 && $jewishMonth == $AV)
      $result[] = "Девятое Ава";
  } else {
    if ($jewishDay == 9 && $jewishMonth == $AV)
      $result[] = "Девятое Ава";
  }
  if ($jewishDay == 15 && $jewishMonth == $AV)
    $result[] = "Ту бэАв";

  return $result;
}
?>
