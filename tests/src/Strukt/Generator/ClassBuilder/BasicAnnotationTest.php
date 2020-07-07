<?php

class BasicAnnotationTest extends PHPUnit\Framework\TestCase{

	public function test(){

		$class = array(

			"declaration"=>array(

				"namespace"=>"Payroll\AuthModule\Router",
				"name"=>"Index",
				"extends"=>"\App\Data\Router",
				"use"=>array(

					"Psr\Http\Message\RequestInterface",
					"Psr\Http\Message\ResponseInterface"
				)
			),
			"properties"=>array(

				array(

					"access"=>"private",
					"scope"=>"static",
					"name"=>"name",
					"value"=>"\"Payroll\AuthModule\Router\Index\""
				),
				array(

					"name"=>"autoGenerated",
					 "value"=>"true"
				)
			),
			"methods"=>array(

				array(

					"name"=>"welcome", 
					"params"=>null,
					"body"=>"//",
					"annotations"=>array(

						"Route"=>"/",
						"Method"=>array(

							"GET", 
							"POST"
						),
						"Provides"=>"application/json"
					)
				),
				array(

					"name"=>"hello", 
					"params"=>array("to"), 
					"body"=>"//",
					"annotations"=>array(

						"Route"=>"/hello/{to:alpha}",
						"Method"=>array(

							"GET", 
							"POST"
						),
						"Provides"=>"application/html"
					)
				),
				array(

					"name"=>"login", 
					"params"=>array(

						"username", 
						"password"
					),
					"body"=>"//",
					"annotations"=>array(
						
						"Route"=>"/login",
						"Method"=>"GET",
						"Secure"=>array(

							"username"=>"admin",
							"password"=>"p@55w0rd"
						),
						"Expects"=>array(

							"username",
							"password"
						)
					)
				)
			)
		);

		$builder = new \Strukt\Generator\ClassBuilder($class["declaration"]);

		foreach($class["properties"] as $property)
			$builder->addProperty($property);

		foreach($class["methods"] as $method)
			$builder->addMethod($method, new \Strukt\Generator\Annotation\Basic($method["annotations"]));

		// exit($builder);
		$ns = sprintf(sprintf("%s\%s", $class["declaration"]["namespace"], $class["declaration"]["name"]));
		$fixture = Strukt\Fs::cat(sprintf("fixtures/app/src/%s.php", str_replace("\\", "/", $ns)));

		// exit($builder);

		$result = sprintf("<?php\n%s", (string)$builder);
		
		$this->assertEquals($result, str_replace("\r", "", $fixture));
	}
}