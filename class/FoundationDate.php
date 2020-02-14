<?php

final class FoundationDate {
  const MINUTE_SECONDS = 60;
  const SEMI_HOUR_SECONDS = 1800;
  const HOUR_SECONDS = 3600;
  const DAY_SECONDS = 86400;

  /**
   * Геттер массив индексов дней недели.
   *
   * @return array
   *   Массив индексов дней недели.
   */
  public static function weekDays() {
    return array(1 => 'Пн', 2 => 'Вт', 3 => 'Ср', 4 => 'Чт', 5 => 'Пт');
  }

  /**
   * Текущая дата.
   *
   * @return string
   *   Строка даты.
   */
  public static function now() {
    return date('d.m.Y', time());
  }

  /**
   * Текущая дата (обратно).
   *
   * @return string
   *   Строка даты.
   */
  public static function nowRev() {
    return date('Y.m.d', time());
  }

  /**
   * Текущая дата и время.
   *
   * @return string
   *   Строка даты.
   */
  public static function nowDateTime() {
    return date('d.m.Y H:i:s', time());
  }

  /**
   * Форматирование даты.
   *
   * @param int $timestamp
   *   Дата в формате Unix Timestamp.
   * @param string $format
   *   (опционально) Формат даты.
   * @param string $default
   *   (опционально) Значение по умолчанию.
   *
   * @return string
   *   Форматированная дата.
   */
  public static function format($timestamp, $format = 'd.m.Y H:i', $default = '-') {
    return $timestamp ? date($format, $timestamp) : $default;
  }

  /**
   * Форматирование даты (краткий формат).
   *
   * @param int $timestamp
   *   Дата в формате Unix Timestamp.
   *
   * @return string
   *   Форматированная дата.
   */
  public static function short($timestamp) {
    return self::format($timestamp, 'd.m.Y');
  }

  /**
   * Геттер даты со смещением в днях (форматированная дата).
   *
   * @param int $timestamp
   *   Значение времени.
   * @param int $offset
   *   Смещение в днях.
   * @param string $format
   *   Значение формата.
   *
   * @return string
   *   Форматированная дата.
   */
  public static function relativeFormat($timestamp, $offset, $format = 'd.m.Y') {
    return date($format, self::relative($timestamp, $offset));
  }

  /**
   * Геттер даты со смещением в днях.
   *
   * @param int $timestamp
   *   Значение времени.
   * @param int $offset
   *   Смещение в днях.
   *
   * @return int
   *   Значение времени.
   */
  public static function relative($timestamp, $offset) {
    list($d, $m, $y) = explode('.', date('d.m.Y', $timestamp));
    return mktime(0, 0, 0, $m, $d + $offset, $y);
  }

  /**
   * Геттер текущей даты со смещением в днях.
   *
   * @param int $offset
   *   Смещение в днях.
   *
   * @return int
   *   Значение времени.
   */
  public static function relativeNow($offset) {
    return self::relative(time(), $offset);
  }

  /**
   * Нормализация даты (к большему).
   *
   * @param int $timestamp
   *   Дата в формате Unix Timestamp.
   * @param int $offset
   *   (опционально) Смещение в днях.
   *
   * @return int
   *   Нормализованое значение даты.
   */
  public static function high($timestamp, $offset = 0) {
    if ($timestamp) {
      list($d, $m, $y) = explode('.', date('d.m.Y', $timestamp));
      $timestamp = mktime(23, 59, 59, $m, $d + $offset, $y);
    }

    return $timestamp;
  }

  /**
   * Нормализация даты (к меньшему).
   *
   * @param int $timestamp
   *   Дата в формате Unix Timestamp.
   * @param int $offset
   *   (опционально) Смещение в днях.
   *
   * @return int
   *   Нормализованое значение даты.
   */
  public static function low($timestamp, $offset = 0) {
    if ($timestamp) {
      list($d, $m, $y) = explode('.', date('d.m.Y', $timestamp));
      $timestamp = mktime(0, 0, 0, $m, $d + $offset, $y);
    }

    return $timestamp;
  }

  /**
   * Дата в формате SQL.
   *
   * @param int $timestamp
   *   UNIX timestamp значение.
   * @param int $offset
   *   (опционально) Смещение в днях.
   *
   * @return string
   *   Строка даты.
   */
  public static function sql($timestamp, $offset = 0) {
    return date('Y-m-d H:i:s', $timestamp + $offset * self::DAY_SECONDS);
  }

  /**
   * Дата в формате SQL.
   *
   * @param int $timestamp
   *   UNIX timestamp значение.
   *
   * @return array
   *   Диапазон.
   */
  public static function sqlYear($timestamp) {
    return array(
      date('Y-01-01 00:00:00', $timestamp),
      date('Y-12-31 23:59:59', $timestamp)
    );
  }

  /**
   * Дата в формате SQL.
   *
   * @param int $timestamp
   *   UNIX timestamp значение.
   *
   * @return string
   *   Дата.
   */
  public static function sqlYearHigh($timestamp) {
    return date('Y-12-31 23:59:59', $timestamp);
  }

  /**
   * Дата в формате SQL (старшая).
   *
   * @param int $timestamp
   *   UNIX timestamp значение.
   * @param int $offset
   *   (опционально) Смещение в днях.
   *
   * @return string
   *   Строка даты.
   */
  public static function sqlHigh($timestamp, $offset = 0) {
    return date('Y-m-d 23:59:59', $timestamp + $offset * self::DAY_SECONDS);
  }

  /**
   * Дата в формате SQL (младшая).
   *
   * @param int $timestamp
   *   UNIX timestamp значение.
   * @param int $offset
   *   (опционально) Смещение в днях.
   *
   * @return string
   *   Строка даты.
   */
  public static function sqlLow($timestamp, $offset = 0) {
    return date('Y-m-d 00:00:00', $timestamp + $offset * self::DAY_SECONDS);
  }

  /**
   * Приведение UTC к формату Unix.
   *
   * @param int $timestamp
   *   UNIX timestamp значение.
   *
   * @return mixed
   *   Дата в формате unix timestamp.
   */
  public static function utc($timestamp) {
    return $timestamp ? floor($timestamp / 1000) : NULL;
  }

  /**
   * Геттер ближайшего дня недели.
   *
   * @param int $timestamp
   *   Значение времени.
   *
   * @return int
   *   Дата ближайшего дня недели.
   */
  public static function relativeWeekDay($timestamp, $week_day, $allow_high = FALSE) {
    if (!$timestamp) {
      $timestamp = REQUEST_TIME;
    }

    // определение времени
    $hour = $allow_high ? 24 : 0;
    $minute = $allow_high ? 59 : 0;
    $second = $allow_high ? 59 : 0;

    list($d, $m, $y, $w) = explode('.', date('d.m.Y.N', $timestamp));
    return mktime($hour, $minute, $second, $m, $d - $w + $week_day, $y);
  }

}
