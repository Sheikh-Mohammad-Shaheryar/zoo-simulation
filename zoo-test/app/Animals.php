<?php

namespace App;


class Animals
{

    const totalAnimalsPerGroup = 5;
    const animals=[
        'Monkey'=> [
            'minHealth'=>30,
            "chance"=>0
        ],
        'Elephant'=> [
                'minHealth'=>70,
                "chance"=>1
            ],
        'Giraffe'=>[
            'minHealth'=>50,
            "chance"=>0
        ]
    ];

    public $animalDataSet=[];
    private $timePassed = 0;
    private $dataFilePath = __DIR__ . '/data.json';

    function __construct()
    {   echo '__construct </br>';
        $this->createAnimalList();
    }

    private function getFileData()
    {
        $data = [];
        if(file_exists($this->dataFilePath))
        {
            $jsonString = file_get_contents($this->dataFilePath);
            $data = json_decode($jsonString, true);

        }
        return $data;
    }

    private function createAnimalList()
    {
        echo 'createAnimalList </br>';
        $animalData = $this->getFileData();

        if(!isset($animalData['isDataInit']))
        {
            echo 'data not found';
            $this->createAnimalsdata();

        }
        elseif(isset($animalData['isDataInit']) && $animalData['isDataInit'] && count($animalData['animalDataSet']) > 0)
        {
            $this->animalDataSet = $animalData['animalDataSet'];
        }
        else
        {
            $this->createAnimalsdata();
        }

    }

    private function createAnimalsdata()
    {
        echo 'createAnimalsdata </br>';
        foreach(self::animals as $key=>$animal){
            for ($i = 1; $i <= self::totalAnimalsPerGroup; $i++)
            {
                $this->animalDataSet[$key][]=[
                    "name" => $key.' '.$i,
                    "health" => 100.0,
                    "alive" => true,
                    "minHealth"=>$animal['minHealth'],
                    "chance"=>$animal['chance'],
                ];
            }
        }

        $fileData = [
            'isDataInit'=>true,
            'totalTimePassed'=>0,
            'totalFeed'=>0,
            'animalDataSet'=>$this->animalDataSet,
        ];

        file_put_contents($this->dataFilePath,json_encode($fileData));
    }

    private function updateAnimalsData()
    {
        echo 'updateAnimalsData </br>';
        $animalData = $this->getFileData();
        if(isset($animalData['isDataInit']) && $animalData['isDataInit'])
        {
            $fileData = [
                'isDataInit'=>true,
                'totalTimePassed'=>$this->timePassed,
                'totalFeed'=>0,
                'animalDataSet'=>$this->animalDataSet,
            ];

            file_put_contents($this->dataFilePath,json_encode($fileData));
        }

    }

    /**
     * @return array
     */
    public function getAnimalStatus()
    {
        echo 'getAnimalStatus </br>';

        $animalData = $this->getFileData();
        $animalStatus = [];

        if(isset($animalData['animalDataSet']) && count($animalData['animalDataSet']) > 0)
        {
            foreach($animalData['animalDataSet'] as $data)
            {
                $animalStatus = array_merge($animalStatus,$data);
            }
        }

        return $animalStatus;
    }

    public function decreaseAnimalHealth()
    {
        echo 'decreaseAnimalHealth </br>';
        foreach($this->animalDataSet as $group => $animal)
        {
            foreach($animal as $index => $animalData)
            {
                $randomValue = rand(0,20);
                $currentHealth = $animalData['health'];

                if($currentHealth < $animalData['minHealth'])
                {
                    if($animalData['chance'] <= 0)
                    {
                        $this->animalDataSet[$group][$index]['alive'] = 0;
                        $this->animalDataSet[$group][$index]['chance'] = 0;
                    }
                    else
                    {
                        $this->animalDataSet[$group][$index]['chance'] = $animalData['chance'] - 1;
                    }

                }
                else
                {
                    $reduceHealthValue = ($randomValue / 100) * $currentHealth;
                    $this->animalDataSet[$group][$index]['health'] = round($animalData['health'] - $reduceHealthValue,2);
                }

            }
        }

        $this->updateAnimalsData();
    }

    public function feedAnimals()
    {
        $totalAnimalsTypes = count($this->animalDataSet);
        $feedingValues = [];

        // create random number according to the animals type
        for($i = 0 ; $i < $totalAnimalsTypes ; $i++)
        {
            $feedingValues[] = rand(10,25);
        }

        $loopIteration = 0;
        foreach($this->animalDataSet as $group => $animal)
        {
            $animalTypeFeedingValue = $feedingValues[$loopIteration];

            foreach($animal as $index => $animalData)
            {
                $currentHealth = $animalData['health'];

                if($currentHealth < 100 && $animalData['alive'])
                {
                    $increaseHealthValue = ($animalTypeFeedingValue / 100) * $currentHealth;
                    $health = round($animalData['health'] + $increaseHealthValue,2);
                    $this->animalDataSet[$group][$index]['health'] = ($health > 100) ? 100: $health;
                }
            }

            $loopIteration++;
        }

        $this->updateAnimalsData();

    }

    /**
     * @param int $hours
     */
    public function updateTimeByHour($hours)
    {
        echo 'updateTimeByHour </br>';
        return $this->timePassed = $this->timePassed + $hours;
    }

    public function resetData()
    {
        file_put_contents($this->dataFilePath,json_encode([]));
        $this->animalDataSet = [];
        $this->createAnimalsdata();
        $this->timePassed = 0;
    }

    public function dd($data)
    {
        echo '<pre>';
        print_r($data);
        echo '</pre>';
    }




}
?>