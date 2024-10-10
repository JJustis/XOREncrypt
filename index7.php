<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Advanced Encryption Chip Simulation using JavaScript">
    <meta name="keywords" content="JavaScript, Encryption, Simulation, Logical Gates">
    <meta name="author" content="Your Name">
    <title>Advanced Encryption Chip Simulation</title>
    <style>
        /* Base styling */
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(to right, #283048, #859398);
            color: #fff;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        /* Container styling */
        .container {
            background: #1c1c1e;
            border-radius: 15px;
            padding: 30px;
            max-width: 800px;
            width: 90%;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.3);
            text-align: center;
        }

        h1 {
            margin: 0 0 20px;
            font-weight: 300;
            color: #76c7c0;
        }

        /* Input form styling */
        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
            margin-bottom: 30px;
        }

        label {
            text-align: left;
            margin-bottom: 5px;
            font-size: 16px;
        }

        input {
            padding: 10px;
            border: 2px solid #3e3e40;
            border-radius: 5px;
            background: #333;
            color: #fff;
            font-size: 16px;
            outline: none;
        }

        input:focus {
            border-color: #76c7c0;
        }

        button {
            padding: 15px;
            border: none;
            border-radius: 5px;
            background: #76c7c0;
            color: #fff;
            font-size: 18px;
            cursor: pointer;
            transition: background 0.3s;
        }

        button:hover {
            background: #60a7a0;
        }

        /* Output and log styling */
        .output {
            margin: 30px 0;
            padding: 20px;
            background: #2c2c2e;
            border-radius: 10px;
            text-align: left;
        }

        .log {
            font-size: 14px;
            font-family: 'Courier New', Courier, monospace;
            background: #1e1e20;
            padding: 10px;
            margin-top: 10px;
            border-radius: 5px;
            overflow-y: auto;
            max-height: 200px;
        }

        /* Responsive styling */
        @media (max-width: 600px) {
            .container {
                padding: 20px;
            }

            button {
                font-size: 16px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Advanced Encryption Chip Simulation</h1>
        <form id="encryptionForm">
            <div>
                <label for="plaintext">Plaintext (Binary):</label>
                <input type="text" id="plaintext" name="plaintext" placeholder="Enter binary sequence" required pattern="[01]+">
            </div>
            <div>
                <label for="key">Key (Binary):</label>
                <input type="text" id="key" name="key" placeholder="Enter binary sequence" required pattern="[01]+">
            </div>
            <div>
                <label for="rounds">Encryption Rounds (e.g., 4):</label>
                <input type="number" id="rounds" name="rounds" placeholder="Number of rounds" value="4" min="1" required>
            </div>
            <button type="submit">Encrypt</button>
        </form>
        <div class="output">
            <h2>Encrypted Output</h2>
            <p id="ciphertext">-</p>
        </div>
        <div class="log">
            <h3>Encryption Rounds Log</h3>
            <pre id="logContent">-</pre>
        </div>
    </div>

    <script>
        // JavaScript implementation of an advanced encryption chip logic for any bit size
        const sBox4Bit = [0x6, 0x4, 0xC, 0x5, 0x0, 0x7, 0x2, 0xE, 0x1, 0xF, 0xB, 0xD, 0xA, 0x9, 0x3, 0x8];

        function xorOperation(data, key) {
            return data ^ key;
        }

        function permute(input, bitSize) {
            let halfSize = bitSize / 2;
            let upperHalf = (input >> halfSize) & ((1 << halfSize) - 1);
            let lowerHalf = input & ((1 << halfSize) - 1);
            return (lowerHalf << halfSize) | upperHalf; // Swap the upper and lower halves
        }

        function keyExpansion(key, rounds, bitSize) {
            let keys = [key];
            for (let i = 1; i < rounds; i++) {
                keys.push((keys[i - 1] + i) & ((1 << bitSize) - 1)); // Ensure the key stays within bitSize
            }
            return keys;
        }

        function sBoxSubstitute(input, bitSize) {
            // Dynamically create S-Box based on input bit size (e.g., 4-bit, 8-bit)
            const sBox = sBox4Bit; // Use 4-bit S-Box for now, expand for larger sizes
            return sBox[input % sBox.length];
        }

        function encryptRound(data, key, bitSize) {
            let xorResult = xorOperation(data, key);
            let substituted = sBoxSubstitute(xorResult, bitSize);
            return permute(substituted, bitSize);
        }

        function encrypt(data, key, rounds, bitSize) {
            const subkeys = keyExpansion(key, rounds, bitSize);
            let currentData = data;
            let logContent = "";

            for (let round = 0; round < rounds; round++) {
                currentData = encryptRound(currentData, subkeys[round], bitSize);
                logContent += `Round ${round + 1} output: ${currentData.toString(2).padStart(bitSize, '0')}\n`;
            }

            document.getElementById("logContent").innerText = logContent;
            return currentData;
        }

        document.getElementById("encryptionForm").addEventListener("submit", function (event) {
            event.preventDefault();

            let plaintext = document.getElementById("plaintext").value;
            let key = document.getElementById("key").value;
            let rounds = parseInt(document.getElementById("rounds").value);

            // Determine bit size dynamically based on the length of the inputs
            let bitSize = Math.max(plaintext.length, key.length);
            let plaintextInt = parseInt(plaintext, 2);
            let keyInt = parseInt(key, 2);

            let ciphertext = encrypt(plaintextInt, keyInt, rounds, bitSize);

            document.getElementById("ciphertext").innerText = `Ciphertext: ${ciphertext.toString(2).padStart(bitSize, '0')}`;
        });
    </script>
</body>
</html>
