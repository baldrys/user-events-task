<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>
</head>

<body>
	<h1>This is the participant email!</h1>
	<p>
		Hey {{ $participant->first_name }}!
		We will be happy to see you in the event {{ $event->name }}
		which will be {{ $event->date }}
	</p>
</body>

</html>