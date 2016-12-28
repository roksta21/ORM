<?php

function startsWith($haystack, $needle)
{
     $length = strlen($needle);
     return (substr($haystack, 0, $length) === $needle);
}

function dd($data1, $data2 = null, $data3 = null, $data4 = null, $data5 = null)
{
	dump($data1);

	if ($data2 != null) {
		dump($data2);
	}

	if ($data3 != null) {
		dump($data3);
	}

	if ($data4 != null) {
		dump($data4);
	}

	if ($data5 != null) {
		dump($data5);
	}

	die();
}

function recast($className, stdClass &$object)
{
    if (!class_exists($className))
        throw new InvalidArgumentException(sprintf('Inexistant class %s.', $className));

    $new = new $className();

    foreach($object as $property => &$value)
    {
        $new->$property = &$value;
        unset($object->$property);
    }
    unset($value);
    $object = (unset) $object;
    
    return $new;
}