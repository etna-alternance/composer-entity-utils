<?php

namespace TestContext;

use ETNA\FeatureContext\BaseContext;

use Symfony\Component\HttpFoundation\File\UploadedFile;

use ETNA\FeatureContext as EtnaFeatureContext;
use ETNA\EntityUtils;

/**
 * Features context
 */
class FeatureContext extends BaseContext
{
    static private $_parameters;
    static private $vhosts;

    private $result    = null;
    private $csv_lines = null;
    private $error     = null;

    /**
     * @Then /^le résultat devrait être identique à "(.*)"$/
     * @Then /^le résultat devrait être identique au JSON suivant :$/
     * @Then /^le résultat devrait ressembler au JSON suivant :$/
     * @param string $string
     */
    public function leResultatDevraitRessemblerAuJsonSuivant($string)
    {
        $expected_result = json_decode($string);
        $real_result     = json_decode(json_encode($this->result));
        if (null === $expected_result) {
            throw new Exception("json_decode error");
        }

        $this->check($expected_result, $real_result, "result", $errors);
        if (0 < ($nb_errors = count($errors))) {
            echo json_encode($real_result, JSON_PRETTY_PRINT);
            throw new Exception("{$nb_errors} errors :\n" . implode("\n", $errors));
        }
    }

    /**
     * @When /^je veux connaître les différences entre "([^"]*)" et "([^"]*)"(?: en excluant "([^"]*)")?(?: avec les traductions "([^"]*)")?$/
     */
    public function jeVeuxConnaitreLesDifferencesEntreEt($old_filename, $new_filename, $exclusions = "", $translations = "")
    {
        $old_filepath = realpath($this->requests_path . "/" . $old_filename);
        $new_filepath = realpath($this->requests_path . "/" . $new_filename);

        $old = json_decode(file_get_contents($old_filepath), true);
        $new = json_decode(file_get_contents($new_filepath), true);

        if ("" !== trim($exclusions)) {
            $exclusion_filepath = realpath($this->requests_path . "/" . $exclusions);
            $exclusions         = json_decode(file_get_contents($exclusion_filepath), true);
        } else {
            $exclusions = [];
        }

        if ("" !== trim($translations)) {
            $translation_filepath = realpath($this->requests_path . "/" . $translations);
            $translations         = json_decode(file_get_contents($translation_filepath), true);
        } else {
            $translations = [];
        }

        $this->result = EntityUtils::getChanges($old, $new, $translations, $exclusions);
    }
}
