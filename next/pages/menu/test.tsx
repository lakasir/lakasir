export interface testProps {}

export function Test(props: testProps): JSX.Element {
  // belajar if else
  // if (maka kondisi yang diharapkan benar) {
  //   console.log("maka kondisi benar maka akan dijalankan")
  // } else {
  //   console.log("maka kondisi salah maka akan dijalankan")
  // }

  // batasan umur untuk masuk ke bioskop

  const orang = [
    {
      umur: 17,
      nama: "Andy",
      tinggi: 170,
      berat: 60,
    },
    {
      umur: 18,
      nama: "Budi",
      tinggi: 170,
      berat: 60,
    },
    {
      umur: 13,
      nama: "Rosiki",
      tinggi: 160,
      berat: 50,
    },
  ];

  for (let i = 0; i < orang.length; i++) {
    if (orang[i].umur >= 17) {
      // console.log(orang[i].nama + " boleh masuk ke bioskop");
    } else {
      // console.log(orang[i].nama + " tidak boleh masuk ke bioskop");
    }
  }



  // larik
  let array = ["satu", "dua", "tiga", "empat", "lima"];
  // indeks dimulai dari 0
  // indeks bahasa indonesia = urutan

  // let 1 inisiasi
  // i < array.length pengkondisian
  // i++ increment
  for (let i = 0; i < array.length; i++) {
    console.log(array[i]);
  }


  // 0 > 0 = false
  // 0 >= 0 = true
  // 0 < 0 = false
  // 0 <= 0 = true

  return <div>Hello World</div>;
}

export default Test;
