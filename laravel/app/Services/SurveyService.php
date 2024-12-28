<?php
namespace App\Services;

class SurveyService
{
    public function __init()
    {

    }

    public function xml_parse_survey($name, $xml)
    {
        $start_byte = mb_strlen('<' . $name . '>');
        $start = mb_strpos($xml, '<' . $name . '>');
        $end = mb_strpos($xml, '</' . $name . '>');
        $return = '';
        if($start !== false && $end !== false && $end){
            $return = mb_substr($xml, $start + $start_byte, $end - $start - $start_byte, 'UTF-8');
        }
        return $return;
    }

    public function get_xml_survey($xml)
    {
        $return['response'] = [];
        $response = $this->xml_parse_survey('response', $xml);
        $return['response']['error'] = $this->xml_parse_survey('error', $response);
        $return['response']['maintenance'] = $this->xml_parse_survey('maintenance', $response);

        $start_byte = mb_strlen('</maintenance>');
        $end = mb_strpos($response, '</maintenance>');
        $maintenance_end = mb_substr($response, $end + $start_byte, null, 'UTF-8');
        $research_list = [];
        $key=0;
        foreach (mb_split('<research>', $maintenance_end) as $research_data) {
            if (empty($research_data)) {
                continue;
            }
            $end = mb_strpos($research_data, '</research>');
            $research = mb_substr($research_data, 0, $end, 'UTF-8');

            $research_list[$key]['id']  = $this->xml_parse_survey('id', $research);
            $research_list[$key]['enquete_name']  = $this->xml_parse_survey('enquete_name', $research);
            $research_list[$key]['enquete_num']  = $this->xml_parse_survey('enquete_num', $research);
            $research_list[$key]['term_start']  = $this->xml_parse_survey('term_start', $research);
            $research_list[$key]['term_end']  = $this->xml_parse_survey('term_end', $research);

            $research_list[$key]['point']  = $this->xml_parse_survey('point', $research);
            $research_list[$key]['answered']  = $this->xml_parse_survey('answered', $research);
            $research_list[$key]['answered_date']  = $this->xml_parse_survey('answered_date', $research);
            $research_list[$key]['flag_end']  = $this->xml_parse_survey('flag_end', $research);
            $research_list[$key]['url']  = $this->xml_parse_survey('url', $research);
            $research_list[$key]['flag_sensitive']  = $this->xml_parse_survey('flag_sensitive', $research);
            $key++;
        }
        $return['response']['research'] = $research_list;

        return json_encode($return);
    }

}