<!DOCTYPE html>
<html>
<head>
	<title>Zoo Simulator</title>
	<style>
		table {
			border-collapse: collapse;
		}

		th, td {
			padding: 10px;
			border: 1px solid black;
		}

		.alive {
			background-color: lightgreen;
		}

		.dead {
			background-color: lightcoral;
		}
	</style>
</head>
<body>
	<h1>Zoo Simulator</h1>

	<table>
		<thead>
			<tr>
				<th>Type</th>
				<th>Name</th>
				<th>Health</th>
				<th>Status</th>
			</tr>
		</thead>
		<tbody>
			<?php
				// Initialize variables
				$animals = array(
					array("type" => "Monkey", "list" => array()),
					array("type" => "Giraffe", "list" => array()),
					array("type" => "Elephant", "list" => array())
				);

				$feedMonkey = rand(10, 25);
				$feedGiraffe = rand(10, 25);
				$feedElephant = rand(10, 25);

				$minHealthMonkey = 30;
				$minHealthGiraffe = 50;
				$minHealthElephant = 70;

				$time = 0;

				// Define functions
				function initAnimals(&$animals) {
					$monkeyNames = array("Bobo", "Mimi", "Kiki", "Coco", "Fifi");
					$giraffeNames = array("Gerald", "Geoffrey", "Grace", "Gina", "Gwen");
					$elephantNames = array("Dumbo", "Ellie", "Eli", "Ella", "Elmer");

					foreach ($animals as &$animalType) {
						for ($i = 0; $i < 5; $i++) {
							$name = $animalType["type"] == "Monkey" ? $monkeyNames[$i] :
									($animalType["type"] == "Giraffe" ? $giraffeNames[$i] : $elephantNames[$i]);

							$animal = array(
								"name" => $name,
								"health" => 100.0,
								"alive" => true
							);

							array_push($animalType["list"], $animal);
						}
					}
				}

				function feedAnimals(&$animals, $feedMonkey, $feedGiraffe, $feedElephant) {
					foreach ($animals as &$animalType) {
						foreach ($animalType["list"] as &$animal) {
							switch ($animalType["type"]) {
								case "Monkey":
									$animal["health"] = min($animal["health"] + ($feedMonkey / 100.0) * $animal["health"], 100.0);
									break;
								case "Giraffe":
									$animal["health"] = min($animal["health"] + ($feedGiraffe / 100.0) * $animal["health"], 100.0);
									break;
								case "Elephant":
									$animal["health"] = min($animal["health"] + ($feedElephant / 100.0) * $animal["health"], 100.0);
									break;
							}
						}
					}
				
				}

				function updateHealth(&$animal, $healthDecrease) {
					$animal["health"] = max($animal["health"] - ($healthDecrease / 100.0) * $animal["health"], 0.0);
					if ($animal["health"] == 0.0) {
						$animal["alive"] = false;
					}
				}

				function updateAnimals(&$animals) {
					foreach ($animals as &$animalType) {
						foreach ($animalType["list"] as &$animal) {
							switch ($animalType["type"]) {
								case "Monkey":
									updateHealth($animal, rand(0, 20));
									break;
								case "Giraffe":
									updateHealth($animal, rand(0, 20));
									break;
								case "Elephant":
									updateHealth($animal, rand(0, 20));
									if ($animal["health"] < $minHealthElephant && $animal["alive"]) {
										$animal["walking"] = false;
									}
									if (!$animal["walking"] && $animal["health"] < $minHealthElephant && $animal["alive"]) {
										updateHealth($animal, rand(0, 20));
										if ($animal["health"] < $minHealthElephant) {
											$animal["alive"] = false;
										} else {
											$animal["walking"] = true;
										}
									}
									break;
							}
						}
					}
				}

				function printAnimals(&$animals) {
					foreach ($animals as &$animalType) {
						foreach ($animalType["list"] as &$animal) {
							$status = $animal["alive"] ? "Alive" : "Dead";
							$class = $animal["alive"] ? "alive" : "dead";

							echo "<tr class=\"$class\">";
							echo "<td>{$animalType["type"]}</td>";
							echo "<td>{$animal["name"]}</td>";
							echo "<td>{$animal["health"]}</td>";
							echo "<td>$status</td>";
							echo "</tr>";
						}
					}
				}

				if ($_SERVER["REQUEST_METHOD"] == "POST") {
					if (isset($_POST["feed"])) {
						feedAnimals($animals, $feedMonkey, $feedGiraffe, $feedElephant);
					} else if (isset($_POST["pass"])) {
						updateAnimals($animals);
						$time++;
					}
				} else {
					initAnimals($animals);
				}

				echo "<p>Time: $time hours</p>";

				echo "<form method=\"post\">";
				echo "<button type=\"submit\" name=\"feed\">Feed</button>";
				echo "<button type=\"submit\" name=\"pass\">Pass Time</button>";
				echo "</form>";

				echo "<table>";
				echo "<thead>";
				echo "<tr>";
				echo "<th>Type</th>";
				echo "<th>Name</th>";
				echo "<th>Health</th>";
				echo "<th>Status</th>";
				echo "</tr>";
				echo "</thead>";
				echo "<tbody>";
				printAnimals($animals);
				echo "</tbody>";
				echo "</table>";
			?>
		</tbody>
	</table>
</body>
</html>
