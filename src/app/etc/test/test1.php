<!DOCTYPE html>
<html>
<head>
  <title>金記キッズ</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 20px;
      background-color: #212121;
    }

    .container {
      max-width: 600px;
      margin: 0 auto;
      padding: 40px;
      background: linear-gradient(to bottom right, #292929, #151515);
      box-shadow: 0 0 20px rgba(255, 255, 255, 0.1);
      border-radius: 10px;
    }

    h1 {
      font-size: 48px;
      text-align: center;
      color: #ffffff;
      text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
    }

    input[type="text"] {
      padding: 12px;
      font-size: 20px;
      border: none;
      border-radius: 4px;
      background-color: #f0f0f0;
      color: #333333;
      width: 100%;
      margin-bottom: 20px;
    }

    button {
      padding: 12px 24px;
      font-size: 20px;
      background-color: #4CAF50;
      color: #ffffff;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      width: 100%;
      margin-bottom: 20px;
      transition: transform 0.3s;
      transform-style: preserve-3d;
      position: relative;
      transition: background-color 0.3s, transform 0.3s;
    }

    button::before {
      content: "";
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(255, 255, 255, 0.2);
      border-radius: 4px;
      transform: translateZ(-8px);
      z-index: -1;
    }

    button:hover {
      background-color: #00cc00;
      transform: scale(1.1);
    }

    h2 {
      font-size: 32px;
      text-align: center;
      color: #ffffff;
      text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
      margin-top: 40px;
      margin-bottom: 20px;
    }

    h3 {
      font-size: 32px;
      text-align: center;
      color: #ffffff;
      text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
      margin-top: 40px;
      margin-bottom: 20px;
    }

    #results {
      background-color: #ffffff;
      padding: 20px;
      border-radius: 4px;
    }

    #results p {
      margin: 0;
      padding: 10px 0;
      font-size: 20px;
      text-align: center;
    }

    .glow {
      animation: glowing 2s ease-in-out infinite;
    }

    @keyframes glowing {
      0% {
        box-shadow: 0 0 5px #ffffff;
      }
      50% {
        box-shadow: 0 0 20px #ffffff;
      }
      100% {
        box-shadow: 0 0 5px #ffffff;
      }
    }

    @keyframes rotate {
      from {
        transform: rotateY(0);
      }
      to {
        transform: rotateY(360deg);
      }
    }
  </style>
</head>
<body>
<div class="container">
    <h1 class="glow">お手伝いガチャ</h1>
    <h3>＜してほしいお手伝いを入力⇩＞</h3>
    <input type="text" id="gachaInput">
    <button onclick="addToGachaPool()">追加する</button>
    <form action="index.php" method="post">
        <input type="submit" value="ガチャ画面に移動">
    </form>
  </div>

  <script src="gacha.js">
    const rotateButtons = document.querySelectorAll(".rotate");
    rotateButtons.forEach(button => {
      button.addEventListener("mouseover", () => {
        button.classList.add("rotate-animation");
      });
      button.addEventListener("mouseout", () => {
        button.classList.remove("rotate-animation");
      });
    });
  </script>
</body>
</html>
