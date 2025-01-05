// การเลือกสีจากวงกลมและแสดงสีที่เข้าคู่กัน
const colorCircle = document.getElementById('color-circle');
const selectedColorBox = document.getElementById('selected-color');
const matchedColorBox = document.getElementById('matched-color');

// ฟังก์ชันในการเลือกสีจากวงกลม
colorCircle.addEventListener('click', () => {
  // รับสีที่คลิกจากวงกลม
  const newColor = getRandomColor();
  colorCircle.style.backgroundColor = newColor;
  changeColor(newColor);
});

// ฟังก์ชันที่ใช้สุ่มสีใหม่
function getRandomColor() {
  return "#" + Math.floor(Math.random() * 16777215).toString(16); // สุ่มสีในรูปแบบ Hex
}

// ฟังก์ชันที่ทำหน้าที่แสดงสีที่เลือกและสีที่เข้าคู่กัน
function changeColor(color) {
  selectedColorBox.style.backgroundColor = color;

  // คำนวณสีที่เข้าคู่กัน
  const matchedColor = getComplementaryColor(color);
  matchedColorBox.style.backgroundColor = matchedColor;
}

// ฟังก์ชันหาคู่สีที่ตรงข้าม (complementary color)
function getComplementaryColor(color) {
  // แปลง Hex color เป็น RGB
  const rgb = hexToRgb(color);
  
  // คำนวณสีตรงข้าม (complementary)
  const compRed = 255 - rgb.r;
  const compGreen = 255 - rgb.g;
  const compBlue = 255 - rgb.b;

  // แปลงกลับเป็น Hex
  return rgbToHex(compRed, compGreen, compBlue);
}

// ฟังก์ชันแปลง Hex เป็น RGB
function hexToRgb(hex) {
  const r = parseInt(hex.slice(1, 3), 16);
  const g = parseInt(hex.slice(3, 5), 16);
  const b = parseInt(hex.slice(5, 7), 16);
  return { r, g, b };
}

// ฟังก์ชันแปลง RGB เป็น Hex
function rgbToHex(r, g, b) {
  return "#" + (1 << 24 | r << 16 | g << 8 | b).toString(16).slice(1).toUpperCase();
}
