<?php
function isJewishLeapYear2($year) {
  if ($year % 19 == 0 || $year % 19 == 3 || $year % 19 == 6 ||
      $year % 19 == 8 || $year % 19 == 11 || $year % 19 == 14 ||
      $year % 19 == 17)
    return true;
  else
    return false;
}

function getJewishMonthName2($jewishMonth, $jewishYear) {
$data=get_option('zmanim_widget');
  $jewishMonthNamesLeap = array(__("Tishri",'zmanim'), 
			__("Heshvan",'zmanim'), 
			__("Kislev",'zmanim'), translate_z("Tevet",$data['accent']),
                        __("Shevat",'zmanim'), __("Adar I",'zmanim'), 
			__("Adar II",'zmanim'), __("Nisan",'zmanim'),
                        __("Iyar",'zmanim'), __("Sivan",'zmanim'), 
			__("Tammuz",'zmanim'), translate_z("Av",$data['accent']), __("Elul",'zmanim'));
  $jewishMonthNamesNonLeap = array(__("Tishri",'zmanim'), 
                        __("Heshvan",'zmanim'), 
                        __("Kislev",'zmanim'), translate_z("Tevet",$data['accent']),
                        __("Shevat",'zmanim'), __("Adar",'zmanim'),         
                        "", __("Nisan",'zmanim'),
                        __("Iyar",'zmanim'), __("Sivan",'zmanim'), 
                        __("Tammuz",'zmanim'), translate_z("Av",$data['accent']), __("Elul",'zmanim'));
  if (isJewishLeapYear2($jewishYear))
    return $jewishMonthNamesLeap[$jewishMonth-1];
  else
    return $jewishMonthNamesNonLeap[$jewishMonth-1];
}

function getJewishHoliday2($jdCurrent, $isDiaspora, $postponeShushanPurimOnSaturday) {
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
    $result[] = __("Erev",'zmanim').' '.__("Rosh Hashanah",'zmanim');

  // Holidays in Tishri
  if ($jewishDay == 1 && $jewishMonth == $TISHRI)
    $result[] = __("Rosh Hashanah",'zmanim')." I";
  if ($jewishDay == 2 && $jewishMonth == $TISHRI)
    $result[] = __("Rosh Hashanah",'zmanim')." II";
  $jd = jewishtojd($TISHRI, 3, $jewishYear);
  $weekdayNo = jddayofweek($jd, 0);
  if ($weekdayNo == $SATURDAY) { // If the 3 Tishri would fall on Saturday ...
    // ... postpone Tzom Gedaliah to Sunday
    if ($jewishDay == 4 && $jewishMonth == $TISHRI)
      $result[] = __("Tzom Gedaliah",'zmanim');
  } else {
    if ($jewishDay == 3 && $jewishMonth == $TISHRI)
      $result[] = __("Tzom Gedaliah",'zmanim');
  }
  if ($jewishDay == 9 && $jewishMonth == $TISHRI)
    $result[] = __("Erev",'zmanim').' '.__("Yom Kippur",'zmanim');
  if ($jewishDay == 10 && $jewishMonth == $TISHRI)
    $result[] = __("Yom Kippur",'zmanim');
  if ($jewishDay == 14 && $jewishMonth == $TISHRI)
    $result[] = __("Erev",'zmanim').' '.__("Sukkot",'zmanim');
  if ($jewishDay == 15 && $jewishMonth == $TISHRI)
    $result[] = __("Sukkot",'zmanim')." I";
  if ($jewishDay == 16 && $jewishMonth == $TISHRI && $isDiaspora)
    $result[] = __("Sukkot",'zmanim')." II";
  if ($isDiaspora) {
    if ($jewishDay >= 17 && $jewishDay <= 20 && $jewishMonth == $TISHRI)
      $result[] = __("Hol Hamoed",'zmanim')." ".__("Sukkot",'zmanim');
  } else {
    if ($jewishDay >= 16 && $jewishDay <= 20 && $jewishMonth == $TISHRI)
      $result[] = __("Hol Hamoed",'zmanim')." ".__("Sukkot",'zmanim');
  }
  if ($jewishDay == 21 && $jewishMonth == $TISHRI)
    $result[] = __("Hoshana Rabbah",'zmanim');
  if ($isDiaspora) {
    if ($jewishDay == 22 && $jewishMonth == $TISHRI)
      $result[] = __("Shemini Azeret",'zmanim');
    if ($jewishDay == 23 && $jewishMonth == $TISHRI)
      $result[] = __("Simchat Torah",'zmanim');
    if ($jewishDay == 24 && $jewishMonth == $TISHRI)
      $result[] = __("Isru Chag",'zmanim');
  } else {
    if ($jewishDay == 22 && $jewishMonth == $TISHRI)
      $result[] = __("Shemini Azeret",'zmanim')."/".__("Simchat Torah",'zmanim');
    if ($jewishDay == 23 && $jewishMonth == $TISHRI)
      $result[] = __("Isru Chag",'zmanim');
  }

  // Holidays in Kislev/Tevet
  $hanukkahStart = jewishtojd($KISLEV, 25, $jewishYear);
  $hanukkahNo = (int) ($jdCurrent-$hanukkahStart+1);
  if ($hanukkahNo == 1) $result[] = __("Hanukkah",'zmanim')." I";
  if ($hanukkahNo == 2) $result[] = __("Hanukkah",'zmanim')." II";
  if ($hanukkahNo == 3) $result[] = __("Hanukkah",'zmanim')." III";
  if ($hanukkahNo == 4) $result[] = __("Hanukkah",'zmanim')." IV";
  if ($hanukkahNo == 5) $result[] = __("Hanukkah",'zmanim')." V";
  if ($hanukkahNo == 6) $result[] = __("Hanukkah",'zmanim')." VI";
  if ($hanukkahNo == 7) $result[] = __("Hanukkah",'zmanim')." VII";
  if ($hanukkahNo == 8) $result[] = __("Hanukkah",'zmanim')." VIII";

  // Holidays in Tevet
  $jd = jewishtojd($TEVET, 10, $jewishYear);
  $weekdayNo = jddayofweek($jd, 0);
  if ($weekdayNo == $SATURDAY) { // If the 10 Tevet would fall on Saturday ...
    // ... postpone Tzom Tevet to Sunday
    if ($jewishDay == 11 && $jewishMonth == $TEVET)
      $result[] = __("Tzom",'zmanim')." ".translate_z("Tevet",$data['accent']);
  } else {
    if ($jewishDay == 10 && $jewishMonth == $TEVET)
      $result[] = __("Tzom",'zmanim')." ".translate_z("Tevet",$data['accent']);
  }

  // Holidays in Shevat
  if ($jewishDay == 15 && $jewishMonth == $SHEVAT)
    $result[] = __("Tu",'zmanim')." ".__("B'Shevat",'zmanim');

  // Holidays in Adar I
  if (isJewishLeapYear2($jewishYear) && $jewishDay == 14 && $jewishMonth == $ADAR_I)
    $result[] = __("Purim",'zmanim')." ".__("Katan",'zmanim');
  if (isJewishLeapYear2($jewishYear) && $jewishDay == 15 && $jewishMonth == $ADAR_I)
    $result[] = __("Shushan",'zmanim').' '.__("Purim",'zmanim')." ".__("Katan",'zmanim');

  // Holidays in Adar or Adar II
  if (isJewishLeapYear2($jewishYear))
    $purimMonth = $ADAR_II;
  else
    $purimMonth = $ADAR;
  $jd = jewishtojd($purimMonth, 13, $jewishYear);
  $weekdayNo = jddayofweek($jd, 0);
  if ($weekdayNo == $SATURDAY) { // If the 13 Adar or Adar II would fall on Saturday ...
    // ... move Ta'anit Esther to the preceding Thursday
    if ($jewishDay == 11 && $jewishMonth == $purimMonth)
      $result[] = __("Ta'anith Esther",'zmanim');
  } else {
    if ($jewishDay == 13 && $jewishMonth == $purimMonth)
      $result[] = __("Ta'anith Esther",'zmanim');
  }
  if ($jewishDay == 14 && $jewishMonth == $purimMonth)
    $result[] = __("Purim",'zmanim');
  if ($postponeShushanPurimOnSaturday) {
    $jd = jewishtojd($purimMonth, 15, $jewishYear);
    $weekdayNo = jddayofweek($jd, 0);
    if ($weekdayNo == $SATURDAY) { // If the 15 Adar or Adar II would fall on Saturday ...
      // ... postpone Shushan Purim to Sunday
      if ($jewishDay == 16 && $jewishMonth == $purimMonth)
        $result[] = __("Shushan",'zmanim').' '.__("Purim",'zmanim');
    } else {
      if ($jewishDay == 15 && $jewishMonth == $purimMonth)
        $result[] = __("Shushan",'zmanim').' '.__("Purim",'zmanim');
    }
  } else {
    if ($jewishDay == 15 && $jewishMonth == $purimMonth)
      $result[] = __("Shushan",'zmanim').' '.__("Purim",'zmanim');
  }

  // Holidays in Nisan
  $shabbatHagadolDay = 14;
  $jd = jewishtojd($NISAN, $shabbatHagadolDay, $jewishYear);
  while (jddayofweek($jd, 0) != $SATURDAY) {
    $jd--;
    $shabbatHagadolDay--;
  }
  if ($jewishDay == $shabbatHagadolDay && $jewishMonth == $NISAN)
    $result[] = translate_z("Shabbat",$data['accent']).' '.__("Hagadol",'zmanim');
  if ($jewishDay == 14 && $jewishMonth == $NISAN)
    $result[] = __("Erev",'zmanim').' '.__("Pesach",'zmanim');
  if ($jewishDay == 15 && $jewishMonth == $NISAN)
    $result[] = __("Pesach",'zmanim')." I";
  if ($jewishDay == 16 && $jewishMonth == $NISAN && $isDiaspora)
    $result[] = __("Pesach",'zmanim')." II";
  if ($isDiaspora) {
    if ($jewishDay >= 17 && $jewishDay <= 20 && $jewishMonth == $NISAN)
      $result[] = __("Hol Hamoed",'zmanim')." ".__("Pesach",'zmanim');
  } else {
    if ($jewishDay >= 16 && $jewishDay <= 20 && $jewishMonth == $NISAN)
      $result[] = __("Hol Hamoed",'zmanim')." ".__("Pesach",'zmanim');
  }
  if ($jewishDay == 21 && $jewishMonth == $NISAN)
    $result[] = __("Pesach",'zmanim')." VII";
  if ($jewishDay == 22 && $jewishMonth == $NISAN && $isDiaspora)
    $result[] = __("Pesach",'zmanim')." VIII";
  if ($isDiaspora) {
    if ($jewishDay == 23 && $jewishMonth == $NISAN)
      $result[] = __("Isru Chag",'zmanim');
  } else {
    if ($jewishDay == 22 && $jewishMonth == $NISAN)
      $result[] = __("Isru Chag",'zmanim');
  }

  $jd = jewishtojd($NISAN, 27, $jewishYear);
  $weekdayNo = jddayofweek($jd, 0);
  if ($weekdayNo == $FRIDAY) { // If the 27 Nisan would fall on Friday ...
    // ... then Yom Hashoah falls on Thursday
    if ($jewishDay == 26 && $jewishMonth == $NISAN)
      $result[] = __("Yom Hashoah",'zmanim');
  } else {
    if ($jewishYear >= 5757) { // Since 1997 (5757) ...
      if ($weekdayNo == $SUNDAY) { // If the 27 Nisan would fall on Friday ...
        // ... then Yom Hashoah falls on Thursday
        if ($jewishDay == 28 && $jewishMonth == $NISAN)
          $result[] = __("Yom Hashoah",'zmanim');
      } else {
        if ($jewishDay == 27 && $jewishMonth == $NISAN)
          $result[] = __("Yom Hashoah",'zmanim');
      }
    } else {
      if ($jewishDay == 27 && $jewishMonth == $NISAN)
        $result[] = __("Yom Hashoah",'zmanim');
    }
  }

  // Holidays in Iyar

  $jd = jewishtojd($IYAR, 4, $jewishYear);
  $weekdayNo = jddayofweek($jd, 0);

  // If the 4 Iyar would fall on Friday or Thursday ...
  // ... then Yom Hazikaron falls on Wednesday and Yom Ha'Atzmaut on Thursday
  if ($weekdayNo == $FRIDAY) {
    if ($jewishDay == 2 && $jewishMonth == $IYAR)
      $result[] = __("Yom Hazikaron",'zmanim');
    if ($jewishDay == 3 && $jewishMonth == $IYAR)
      $result[] = __("Yom Ha'Atzmaut",'zmanim');
  } else {
    if ($weekdayNo == $THURSDAY) {
      if ($jewishDay == 3 && $jewishMonth == $IYAR)
        $result[] = __("Yom Hazikaron",'zmanim');
      if ($jewishDay == 4 && $jewishMonth == $IYAR)
        $result[] = __("Yom Ha'Atzmaut",'zmanim');
    } else {
      if ($jewishYear >= 5764) { // Since 2004 (5764) ...
        if ($weekdayNo == $SUNDAY) { // If the 4 Iyar would fall on Sunday ...
          // ... then Yom Hazicaron falls on Monday
          if ($jewishDay == 5 && $jewishMonth == $IYAR)
            $result[] = __("Yom Hazikaron",'zmanim');
          if ($jewishDay == 6 && $jewishMonth == $IYAR)
            $result[] = __("Yom Ha'Atzmaut",'zmanim');
        } else {
          if ($jewishDay == 4 && $jewishMonth == $IYAR)
            $result[] = __("Yom Hazikaron",'zmanim');
          if ($jewishDay == 5 && $jewishMonth == $IYAR)
            $result[] = __("Yom Ha'Atzmaut",'zmanim');
        }
      } else {
        if ($jewishDay == 4 && $jewishMonth == $IYAR)
          $result[] = __("Yom Hazikaron",'zmanim');
        if ($jewishDay == 5 && $jewishMonth == $IYAR)
          $result[] = __("Yom Ha'Atzmaut",'zmanim');
      }
    }
  }

  if ($jewishDay == 14 && $jewishMonth == $IYAR)
    $result[] = __("Pesach",'zmanim').' '.__("Sheini",'zmanim');
  if ($jewishDay == 18 && $jewishMonth == $IYAR)
    $result[] = __("Lag B'Omer",'zmanim');
  if ($jewishDay == 28 && $jewishMonth == $IYAR)
    $result[] = __("Yom Yerushalayim",'zmanim');

  // Holidays in Sivan
  if ($jewishDay == 5 && $jewishMonth == $SIVAN)
    $result[] = __("Erev",'zmanim').' '.__("Shavuot",'zmanim');
  if ($jewishDay == 6 && $jewishMonth == $SIVAN)
    $result[] = __("Shavuot",'zmanim')." I";
  if ($jewishDay == 7 && $jewishMonth == $SIVAN && $isDiaspora)
    $result[] = __("Shavuot",'zmanim')." II";
  if ($isDiaspora) {
    if ($jewishDay == 8 && $jewishMonth == $SIVAN)
      $result[] = __("Isru Chag",'zmanim');
  } else {
    if ($jewishDay == 7 && $jewishMonth == $SIVAN)
      $result[] = __("Isru Chag",'zmanim');
  }

  // Holidays in Tammuz
  $jd = jewishtojd($TAMMUZ, 17, $jewishYear);
  $weekdayNo = jddayofweek($jd, 0);
  if ($weekdayNo == $SATURDAY) { // If the 17 Tammuz would fall on Saturday ...
    // ... postpone Tzom Tammuz to Sunday
    if ($jewishDay == 18 && $jewishMonth == $TAMMUZ)
      $result[] = __("Tzom",'zmanim').' '.__("Tammuz",'zmanim');
  } else {
    if ($jewishDay == 17 && $jewishMonth == $TAMMUZ)
      $result[] = __("Tzom",'zmanim').' '.__("Tammuz",'zmanim');
  }
  
  // Holidays in Av
  $jd = jewishtojd($AV, 9, $jewishYear);
  $weekdayNo = jddayofweek($jd, 0);
  if ($weekdayNo == $SATURDAY) { // If the 9 Av would fall on Saturday ...
    // ... postpone Tisha B'Av to Sunday
    if ($jewishDay == 10 && $jewishMonth == $AV)
      $result[] = __("Tisha B'",'zmanim').' '.translate_z("Av",$data['accent']);
  } else {
    if ($jewishDay == 9 && $jewishMonth == $AV)
      $result[] = __("Tisha B'",'zmanim').' '.translate_z("Av",$data['accent']);
  }
  if ($jewishDay == 15 && $jewishMonth == $AV)
    $result[] = __("Tu B'",'zmanim').' '.translate_z("Av",$data['accent']);

  return $result;
}
?>
