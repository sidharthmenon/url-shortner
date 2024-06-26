<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="refresh" content="0; url='{{$url}}'" />
    <title>{{$title}}</title>
    <meta name="title" content="{{$title}}" />
    <meta name="description" content="{{$description}}" />
    <meta name="image" content="{{$image}}" />
    <meta property="og:image" content="{{$image}}" />
    <meta property="twitter:image" content="{{$image}}" />
    <link rel="canonical" href="{{$canonical}}" />
  </head>
  <style>
    svg{
      width: 40px;
      height: 40px;
      display:inline-block;
    }
    p {
      display: flex;
      align-items: center;
      gap: 5px;
    }
  </style>
  <body>
    <p>
      <svg version="1.1" id="L9" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
        viewBox="0 0 100 100" enable-background="new 0 0 0 0" xml:space="preserve">
          <path fill="#00afef" d="M73,50c0-12.7-10.3-23-23-23S27,37.3,27,50 M30.9,50c0-10.5,8.5-19.1,19.1-19.1S69.1,39.5,69.1,50">
            <animateTransform 
              attributeName="transform" 
              attributeType="XML" 
              type="rotate"
              dur="1s" 
              from="0 50 50"
              to="360 50 50" 
              repeatCount="indefinite" />
          </path>
      </svg>
      You will be redirected shortly!... <a href="{{$url}}">Redirect Now</a>
    </p>
  </body>
</html>