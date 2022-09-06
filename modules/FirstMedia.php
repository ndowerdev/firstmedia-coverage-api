<?php

use DiDom\Document;

class FirstMedia
{
  /**
   * Status : all, never, active, disconnect
   */
  public static function searchArea($query = 'batam', $status = 'all', $page = 1, $api = true)
  {
    // overwrite
    if (isset($_GET['query'])) {
      $query = $_GET['query'];
    }
    if ($query === '') {
      $query = 'batam';
    }
    if (isset($_GET['status'])) {
      $status = $_GET['status'];
    }
    $query = str_replace(' ', '+', $query);

    $endPoint = "https://www.firstmedia.com/ajax/address-by-keyword?keyword={$query}&limit=100";
    $raw = self::cUrl($endPoint);


    $data = json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $raw), false);

    $res = [];
    if ($data->view) {
      $dom = new Document($data->view);
      if ($dom->has('a.list-group-item')) {
        $links = $dom->find('a.list-group-item');
        foreach ($links as $link) {
          switch ($status) {
            case 'all':
              $res[] = [
                'site_id' => $link->{'data-site-id'},
                'building_name' => $link->{'data-building-name'},
                'street_name' => $link->{'data-street-name'},
                'street_no' => $link->{'data-street-no'},
                'district' => $link->{'data-district'},
                'locality' => $link->{'data-locality'},
                'city' => $link->{'data-city'},
                'country' => $link->{'data-country'},
                'postal_code' => $link->{'data-postal-code'},
                'a1' => $link->{'data-a1'},
                'a2' => $link->{'data-a2'},
                'a3' => $link->{'data-a3'},
                'a4' => $link->{'data-a4'},
                'full_address' => $link->{'data-full-address'},
                'site_status' => $link->{'data-site-status'},
                'status' => $link->{'data-status'},
                'source' => $link->{'data-source'},
              ];
              break;
            case 'active':
              if ($link->{'data-status'} !== 'active') {
                continue 2;
              }
              $res[] = [
                'site_id' => $link->{'data-site-id'},
                'building_name' => $link->{'data-building-name'},
                'street_name' => $link->{'data-street-name'},
                'street_no' => $link->{'data-street-no'},
                'district' => $link->{'data-district'},
                'locality' => $link->{'data-locality'},
                'city' => $link->{'data-city'},
                'country' => $link->{'data-country'},
                'postal_code' => $link->{'data-postal-code'},
                'a1' => $link->{'data-a1'},
                'a2' => $link->{'data-a2'},
                'a3' => $link->{'data-a3'},
                'a4' => $link->{'data-a4'},
                'full_address' => $link->{'data-full-address'},
                'site_status' => $link->{'data-site-status'},
                'status' => $link->{'data-status'},
                'source' => $link->{'data-source'},
              ];
              break;
            case 'never':
              if ($link->{'data-status'} !== 'never') {
                continue 2;
              }
              $res[] = [
                'site_id' => $link->{'data-site-id'},
                'building_name' => $link->{'data-building-name'},
                'street_name' => $link->{'data-street-name'},
                'street_no' => $link->{'data-street-no'},
                'district' => $link->{'data-district'},
                'locality' => $link->{'data-locality'},
                'city' => $link->{'data-city'},
                'country' => $link->{'data-country'},
                'postal_code' => $link->{'data-postal-code'},
                'a1' => $link->{'data-a1'},
                'a2' => $link->{'data-a2'},
                'a3' => $link->{'data-a3'},
                'a4' => $link->{'data-a4'},
                'full_address' => $link->{'data-full-address'},
                'site_status' => $link->{'data-site-status'},
                'status' => $link->{'data-status'},
                'source' => $link->{'data-source'},
              ];
              break;
            case 'disconnect':
              if ($link->{'data-status'} !== 'disconnect') {
                continue 2;
              }
              $res[] = [
                'site_id' => $link->{'data-site-id'},
                'building_name' => $link->{'data-building-name'},
                'street_name' => $link->{'data-street-name'},
                'street_no' => $link->{'data-street-no'},
                'district' => $link->{'data-district'},
                'locality' => $link->{'data-locality'},
                'city' => $link->{'data-city'},
                'country' => $link->{'data-country'},
                'postal_code' => $link->{'data-postal-code'},
                'a1' => $link->{'data-a1'},
                'a2' => $link->{'data-a2'},
                'a3' => $link->{'data-a3'},
                'a4' => $link->{'data-a4'},
                'full_address' => $link->{'data-full-address'},
                'site_status' => $link->{'data-site-status'},
                'status' => $link->{'data-status'},
                'source' => $link->{'data-source'},
              ];
              break;

            default:
              $res[] = [
                'site_id' => $link->{'data-site-id'},
                'building_name' => $link->{'data-building-name'},
                'street_name' => $link->{'data-street-name'},
                'street_no' => $link->{'data-street-no'},
                'district' => $link->{'data-district'},
                'locality' => $link->{'data-locality'},
                'city' => $link->{'data-city'},
                'country' => $link->{'data-country'},
                'postal_code' => $link->{'data-postal-code'},
                'a1' => $link->{'data-a1'},
                'a2' => $link->{'data-a2'},
                'a3' => $link->{'data-a3'},
                'a4' => $link->{'data-a4'},
                'full_address' => $link->{'data-full-address'},
                'site_status' => $link->{'data-site-status'},
                'status' => $link->{'data-status'},
                'source' => $link->{'data-source'},
              ];
              break;
          }
        }
      }
    }
    $finalRes = [
      'query' => $query,
      'status' => $status,
      'page' => $page,
      'is_api' => $api,
      'data' => $res
    ];


    if ($api === true) {

      Flight::json($finalRes);
    } else {
      return $finalRes;
    }




    // try {
    //   $data = json_decode(file_get_contents($endPoint));
    // } catch (\Throwable $th) {
    //   throw $th;
    // }
    // if ($data) {
    //   print_r($data);
    // }
  }

  private static function cUrl($endPoint)
  {
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $endPoint);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

    // curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');

    $headers = array();
    $headers[] = 'Accept: */*';
    $headers[] = 'Accept-Language: en-US,en;q=0.9';
    $headers[] = 'Cache-Control: no-cache';
    $headers[] = 'Connection: keep-alive';
    // $headers[] = 'Cookie: _gcl_au=1.1.501348096.1661732107; _tt_enable_cookie=1; _ttp=b813eaa4-743d-4897-b056-340d553fe4be; _hjSessionUser_2337238=eyJpZCI6IjMwNDA2NzM0LWQ2MDctNWUxNy1hMGJlLTdiZDg0MGYzNmM3NSIsImNyZWF0ZWQiOjE2NjE3MzIxMDc0NjMsImV4aXN0aW5nIjp0cnVlfQ==; gaClientID=2069721006.1661732107; custPage=home; custPromo=non promo; custSource=org; custDevice=Win32; custBrowser=Chrome; G_ENABLED_IDPS=google; _gid=GA1.2.1174601574.1662248489; _ga=GA1.1.2069721006.1661732107; _hjIncludedInSessionSample=0; _hjSession_2337238=eyJpZCI6IjBkYWUxZTkyLTQ5NmQtNDljOC1hOTJkLTNkNDc0N2IyYTRkZiIsImNyZWF0ZWQiOjE2NjIyNDg0ODk3OTIsImluU2FtcGxlIjpmYWxzZX0=; _hjAbsoluteSessionInProgress=0; XSRF-TOKEN=eyJpdiI6IjlMd3g3Sk9zRzVOazloWGpKK1RBZVE9PSIsInZhbHVlIjoiVXlENlNSQ1p3czkrdWlSRG9SeVNzRTd1UGhuVmYwclV1Z05QSmZSTFFLU2U2RFwvcGMzcWlEMWxoa3A3dTh4UmgiLCJtYWMiOiJiYjc2NWU5NDIzMDRkMjdmMWY1NGRkYTBkZTE1Mzk1YWZhZjgxNTZhMmMzZWI1ZTY2NmQ1MzRjNTA2NTQ0M2U2In0%3D; firstmedia_session=eyJpdiI6IktkTDdpb0hCb0VwaVdHRVhjRkZxYnc9PSIsInZhbHVlIjoiRlgyS0JNaVpmTGFIRmhmXC9UTmlrZlwvUEVoOGpCa2NPWUM0V1FQRHlUTkYxNlhKOWtzVmxqTDJMNzF0SWxSb0dwWU1CVWZHTzNXMGh0WVIxeDlJalwvSys4eTJrcTJWQXBMN2M5c0FNS3hEbkh2RnRcL212ekFsQmsyNjlmcmRwR0VRIiwibWFjIjoiM2U0MGQ2OTFhZjkyYjU5ZmQxYjhmOTk4ZWUyYmRjYWE4MmIxMTJkNjBiZDM3MzJhN2IzMzJiZWE5YjEwMjY2ZCJ9; TS01ae6ec3=01259512d4e37b6935ce4e2a40bcf16ae31df2336c2feec961ab5d7e570783d1bedf86b5e5b7d961aedd5167623bd8a866cd4ba8f1098358388d6531feb61999041c7fb01f345979594c3767320759c58b0c8952df; TS2c1e2900027=08197c998eab20008cbf4ba925c4ab60044620f2a4514d6dd4d4c17c7d059aea21aed198d8618ab7082dc0e04a113000ab657dc7564b9742527e700717832077fd2fa56bb61c89a15d11177e6eb3ea8c3ece1b5c8e97d70c8161aed91709825e; _ga_MCXQRMYH5Y=GS1.1.1662248489.2.1.1662248875.0.0.0';
    $headers[] = 'Pragma: no-cache';
    $headers[] = 'Referer: https://www.firstmedia.com/get/subscribe/cek-area-layanan';
    $headers[] = 'Sec-Fetch-Dest: empty';
    $headers[] = 'Sec-Fetch-Mode: cors';
    $headers[] = 'Sec-Fetch-Site: same-origin';
    $headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/104.0.0.0 Safari/537.36';
    $headers[] = 'X-Csrf-Token: eiuResYDux9gD9dszwd4zCVWfoWDeRegM1NDGaOd';
    $headers[] = 'X-Firstmedia-Token: X6ADQTN703OUXYXF0MSOSU98KJPYZ5968GT5';
    $headers[] = 'X-Requested-With: XMLHttpRequest';
    $headers[] = 'Sec-Ch-Ua: \"Chromium\";v=\"104\", \" Not A;Brand\";v=\"99\", \"Google Chrome\";v=\"104\"';
    $headers[] = 'Sec-Ch-Ua-Mobile: ?0';
    $headers[] = 'Sec-Ch-Ua-Platform: \"Windows\"';
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $result = curl_exec($ch);
    if (curl_errno($ch)) {
      echo 'Error:' . curl_error($ch);
    }
    curl_close($ch);
    return $result;
  }
}
