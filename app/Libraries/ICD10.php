<?php

namespace App\Libraries;

class ICD10
{
    private $codes = [
        'A00-B99' => 'Certain infectious and parasitic diseases',
        'C00-D48' => 'Neoplasms',
        'D50-D89' => 'Diseases of the blood and blood-forming organs and certain disorders involving the immune mechanism',
        'E00-E90' => 'Endocrine, nutritional and metabolic diseases',
        'F00-F99' => 'Mental and behavioural disorders',
        'G00-G99' => 'Diseases of the nervous system',
        'H00-H59' => 'Diseases of the eye and adnexa',
        'H60-H95' => 'Diseases of the ear and mastoid process',
        'I00-I99' => 'Diseases of the circulatory system',
        'J00-J99' => 'Diseases of the respiratory system',
        'K00-K93' => 'Diseases of the digestive system',
        'L00-L99' => 'Diseases of the skin and subcutaneous tissue',
        'M00-M99' => 'Diseases of the musculoskeletal system and connective tissue',
        'N00-N99' => 'Diseases of the genitourinary system',
        'O00-O99' => 'Pregnancy, childbirth and the puerperium',
        'P00-P96' => 'Certain conditions originating in the perinatal period',
        'Q00-Q99' => 'Congenital malformations, deformations and chromosomal abnormalities',
        'R00-R99' => 'Symptoms, signs and abnormal clinical and laboratory findings, not elsewhere classified',
        'S00-T98' => 'Injury, poisoning and certain other consequences of external causes',
        'V01-Y98' => 'External causes of morbidity and mortality',
        'Z00-Z99' => 'Factors influencing health status and contact with health services',
        'U00-U99' => 'Codes for special purposes'
    ];

    private $specificCodes = [
        'I10' => 'Essential (primary) hypertension',
        'E11' => 'Type 2 diabetes mellitus',
        'J45' => 'Asthma',
        'M54.5' => 'Low back pain',
        'K29.7' => 'Gastritis, unspecified',
        'J02.0' => 'Streptococcal pharyngitis',
        'N39.0' => 'Urinary tract infection, site not specified',
        'J00' => 'Acute nasopharyngitis [common cold]',
        'L20' => 'Atopic dermatitis',
        'G43' => 'Migraine',
        'F41.1' => 'Generalized anxiety disorder',
        'F32.9' => 'Major depressive disorder, single episode, unspecified',
        'M25.5' => 'Pain in joint',
        'R51' => 'Headache',
        'R10.4' => 'Other and unspecified abdominal pain',
    ];

    public function search($query)
    {
        $query = strtoupper(trim($query));
        $results = [];

        // Search in main categories
        foreach ($this->codes as $code => $description) {
            if (strpos($code, $query) !== false || stripos($description, $query) !== false) {
                $results[] = [
                    'code' => $code,
                    'description' => $description,
                    'type' => 'category'
                ];
            }
        }

        // Search in specific codes
        foreach ($this->specificCodes as $code => $description) {
            if (strpos($code, $query) !== false || stripos($description, $query) !== false) {
                $results[] = [
                    'code' => $code,
                    'description' => $description,
                    'type' => 'specific'
                ];
            }
        }

        return $results;
    }

    public function getDetails($code)
    {
        $code = strtoupper(trim($code));
        
        if (isset($this->specificCodes[$code])) {
            return [
                'code' => $code,
                'description' => $this->specificCodes[$code],
                'type' => 'specific'
            ];
        }

        foreach ($this->codes as $range => $description) {
            list($start, $end) = explode('-', $range);
            if ($this->isInRange($code, $start, $end)) {
                return [
                    'code' => $range,
                    'description' => $description,
                    'type' => 'category'
                ];
            }
        }

        return null;
    }

    private function isInRange($code, $start, $end)
    {
        // Extract the letter prefix and number
        preg_match('/([A-Z])(\d+)/', $code, $codeParts);
        preg_match('/([A-Z])(\d+)/', $start, $startParts);
        preg_match('/([A-Z])(\d+)/', $end, $endParts);

        if (!$codeParts || !$startParts || !$endParts) {
            return false;
        }

        if ($codeParts[1] === $startParts[1] && $codeParts[1] === $endParts[1]) {
            $codeNum = (int)$codeParts[2];
            $startNum = (int)$startParts[2];
            $endNum = (int)$endParts[2];
            return $codeNum >= $startNum && $codeNum <= $endNum;
        }

        return false;
    }
}