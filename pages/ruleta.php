<?php

$premios_disponibles = "SELECT * FROM premios WHERE cantidad >= 1";

$consulta_premios = mysqli_query($conexion, $premios_disponibles) or die(mysqli_error($conexion));
$premios = [];
while ($registro = mysqli_fetch_array($consulta_premios)) {
    $premios[] = $registro;
}

?>

<html>

<head>
    <title>Aniversario Aliados 2024</title>
    <link rel="stylesheet" href="assets/css/main.css" type="text/css" />
    <link rel="shortcut icon" href="assets/images/iconlogo.png" type="image/x-icon">
    <script type="text/javascript" src="assets/js/Winwheel.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/latest/TweenMax.min.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

</head>

<body style="background-size: 100% 100%" background="assets/images/bg_blur.jpg">
    <div align="center">
        <table cellpadding="0" cellspacing="0" border="0">
            <tr>
                <td>
                    <div class="power_controls">
                        <br />
                        <br />
                        <table class="power" cellpadding="10" cellspacing="0">
                            <tr>
                                <td align="center" id="pw2" onClick="startSpin()">Lanzar la ruleta</td>
                            </tr>
                        </table>
                        <br />
                    </div>
                </td>
                <td width="438" height="582" class="the_wheel" align="center" valign="center">
                    <canvas id="canvas" width="434" height="434">
                        <p style="color: white" align="center">Sorry, your browser doesn't support canvas. Please try
                            another.</p>
                    </canvas>
                </td>
            </tr>
        </table>
        <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            let premios = <?php echo json_encode($premios); ?>;
            let segments = [];
            const colors = ['#ee1c24', '#3cb878', '#f6989d', '#00aef0', '#ffffff', '#ee1c24', '#3cb878', '#f6989d', '#00aef0', '#ffffff', '#ee1c24', '#3cb878', '#f6989d', '#00aef0', '#ffffff', '#ee1c24', '#3cb878', '#f6989d', '#00aef0', '#ffffff', '#ee1c24', '#3cb878', '#f6989d', '#00aef0', '#ffffff', '#ee1c24', '#3cb878', '#f6989d', '#00aef0', '#ffffff', '#ee1c24', '#3cb878', '#f6989d', '#00aef0', '#ffffff', '#ee1c24', '#3cb878', '#f6989d', '#00aef0', '#ffffff', '#ee1c24', '#3cb878', '#f6989d', '#00aef0', '#ffffff'];
            premios.forEach(premio => {
                segments.push({
                    'fillStyle': colors[segments.length],
                    'text': premio.nombre
                });
            });
            //Function to get random Int on interval [min, max)
            //https://developer.mozilla.org/es/docs/Web/JavaScript/Referencia/Objetos_globales/Math/random
            function getRandomInt(min, max) {
                return Math.floor(Math.random() * (max - min)) + min;
            }
            function obtenerPremio() {
                let cantidad_premios = premios.length;
                let part = getRandomInt(0, cantidad_premios);
                return theWheel.getRandomForSegment(part)
            }
            // Create new wheel object specifying the parameters at creation time.
            let theWheel = new Winwheel({
                'outerRadius': 212,        // Set outer radius so wheel fits inside the background.
                'innerRadius': 75,         // Make wheel hollow so segments don't go all way to center.
                'textFontSize': 14,         // Set default font size for the segments.
                'textOrientation': 'horizontal', // Make text vertial so goes down from the outside of wheel.
                'textAlignment': 'center',    // Align text to outside of wheel.
                'numSegments': segments.length,         // Specify number of segments.
                'segments':             // Define segments including colour and text.
                    segments,
                'animation':           // Specify the animation to use.
                {
                    'type': 'spinToStop',
                    'duration': 10,    // Duration in seconds.
                    'spins': 3,     // Default number of complete spins.
                    'callbackFinished': alertPrize,
                    'callbackSound': playSound,   // Function to call when the tick sound is to be triggered.
                    'soundTrigger': 'pin'        // Specify pins are to trigger the sound, the other option is 'segment'.
                },
                'pins':				// Turn pins on.
                {
                    'number': segments.length,
                    'fillStyle': 'silver',
                    'outerRadius': 4,
                }
            });

            // Loads the tick audio sound in to an audio object.
            let audio = new Audio('assets/audio/tick.mp3');

            // This function is called when the sound is to be played.
            function playSound() {
                // Stop and rewind the sound if it already happens to be playing.
                audio.pause();
                audio.currentTime = 0;

                // Play the sound.
                audio.play();
            }

            // Vars used by the code in this page to do power controls.
            let wheelPower = 0;
            let wheelSpinning = false;

            // -------------------------------------------------------
            // Function to handle the onClick on the power buttons.
            // -------------------------------------------------------
            function powerSelected(powerLevel) {
                // Ensure that power can't be changed while wheel is spinning.
                if (wheelSpinning == false) {
                    // Reset all to grey incase this is not the first time the user has selected the power.
                    document.getElementById('pw1').className = "";
                    document.getElementById('pw2').className = "";
                    document.getElementById('pw3').className = "";

                    // Now light up all cells below-and-including the one selected by changing the class.
                    if (powerLevel >= 1) {
                        document.getElementById('pw1').className = "pw1";
                    }

                    if (powerLevel >= 2) {
                        document.getElementById('pw2').className = "pw2";
                    }

                    if (powerLevel >= 3) {
                        document.getElementById('pw3').className = "pw3";
                    }

                    // Set wheelPower var used when spin button is clicked.
                    wheelPower = powerLevel;

                    // Light up the spin button by changing it's source image and adding a clickable class to it.
                    document.getElementById('spin_button').src = "spin_on.png";
                    document.getElementById('spin_button').className = "clickable";
                }
            }

            // -------------------------------------------------------
            // Click handler for spin button.
            // -------------------------------------------------------
            function startSpin() {
                // Ensure that spinning can't be clicked again while already running.
                if (wheelSpinning == false) {
                    // Based on the power level selected adjust the number of spins for the wheel, the more times is has
                    // to rotate with the duration of the animation the quicker the wheel spins.
                    theWheel.animation.spins = 6;

                    // Disable the spin button so can't click again while wheel is spinning.
                    // This formula always makes the wheel stop somewhere inside the segment selected
                    let stopAt = obtenerPremio();

                    // Important thing is to set the stopAngle of the animation before stating the spin.
                    theWheel.animation.stopAngle = stopAt;
                    // Begin the spin animation by calling startAnimation on the wheel object.
                    theWheel.startAnimation();

                    // Set to true so that power can't be changed and spin button re-enabled during
                    // the current animation. The user will have to reset before spinning again.
                    wheelSpinning = true;
                }
            }

            // -------------------------------------------------------
            // Called when the spin animation has finished by the callback feature of the wheel because I specified callback in the parameters.
            // -------------------------------------------------------
            function alertPrize(indicatedSegment) {
                // Just alert to the user what happened.
                let premio = indicatedSegment.text;
                let id_premio = premios.find(p => p.nombre === premio).id;
                let id_participacion = <?php echo $id_participacion; ?>;
                Swal.fire({
                        title: 'Felicidades!',
                        show_cancel_button: false,
                        text: 'Has ganado el siguiente premio: ' + premio,
                        icon: 'success'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            //llamado ajax a guardarSorteo.php y despues recarga
                            $.ajax({
                                url: 'controllers/guardarSorteo.php',
                                type: 'POST',
                                data: {
                                    id_participacion: id_participacion,
                                    id_premio: id_premio
                                },
                                success: function(response) {
                                    location.reload();
                                }
                            });
                        }
                    });
            }
        </script>
</body>

</html>