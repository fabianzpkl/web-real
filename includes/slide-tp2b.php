<section id="slider">
  <input type="radio" name="slider" id="s1" checked disabled="true">
  <input type="radio" name="slider" id="s2" disabled="true">
  <input type="radio" name="slider" id="s3" disabled="true">

  <label for="s1" id="slide1"></label>
  <label for="s2" id="slide2"></label>
  <label for="s3" id="slide3"></label>

</section>

<style>
#slider {
  height: 400px;
  position: relative;
  perspective: 1000px;
  transform-style: preserve-3d;
}

#slider label {
  margin: auto;
  width: 60%;
  height: 100%;
  border-radius: 0;
  position: absolute;
  left: 0;
  right: 0;
  transition: transform 0.4s ease;
  background-position: center center;
  background-size: cover;
}

#s1:checked ~ #slide4, #s2:checked ~ #slide5,
#s3:checked ~ #slide1, #s4:checked ~ #slide2,
#s5:checked ~ #slide3 {
  box-shadow: 0 1px 4px 0 rgba(0,0,0,.37);
  transform: translate3d(-30%,0,-200px);
}

#s1:checked ~ #slide5, #s2:checked ~ #slide1,
#s3:checked ~ #slide2, #s4:checked ~ #slide3,
#s5:checked ~ #slide4 {
  box-shadow: 0 6px 10px 0 rgba(0,0,0,.3), 0 2px 2px 0 rgba(0,0,0,.2);
  transform: translate3d(-15%,0,-100px);
}

#s1:checked ~ #slide1, #s2:checked ~ #slide2,
#s3:checked ~ #slide3, #s4:checked ~ #slide4,
#s5:checked ~ #slide5 {
  box-shadow: 0 13px 25px 0 rgba(0,0,0,.3), 0 11px 7px 0 rgba(0,0,0,.19);
  transform: translate3d(0,0,0);
}

#s1:checked ~ #slide2, #s2:checked ~ #slide3,
#s3:checked ~ #slide4, #s4:checked ~ #slide5,
#s5:checked ~ #slide1 {
  box-shadow: 0 6px 10px 0 rgba(0,0,0,.3), 0 2px 2px 0 rgba(0,0,0,.2);
  transform: translate3d(15%,0,-100px);
}

#s1:checked ~ #slide3, #s2:checked ~ #slide4,
#s3:checked ~ #slide5, #s4:checked ~ #slide1,
#s5:checked ~ #slide2 {
  box-shadow: 0 1px 4px 0 rgba(0,0,0,.37);
  transform: translate3d(30%,0,-200px);
}

#slide1 { background: url('http://real.local/wp-content/uploads/2024/01/tp2b1.png') }
#slide2 { background: url('http://real.local/wp-content/uploads/2024/01/tp2b2.png') }
#slide3 { background: url('http://real.local/wp-content/uploads/2024/01/tp2b3.png') }
</style>