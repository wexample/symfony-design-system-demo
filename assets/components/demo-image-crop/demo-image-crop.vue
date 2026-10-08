<script>
import ImageCrop from '@wexample/symfony-design-system/components/image-crop/image-crop.vue';

// Two cuts a page asks for: a portrait, square and shown round, and a
// signature, free, on a transparent ground. The images are drawn here rather
// than shipped, and any image of the reader's can take their place.
function drawPortrait() {
  const canvas = document.createElement('canvas');
  canvas.width = 900;
  canvas.height = 600;
  const context = canvas.getContext('2d');
  const sky = context.createLinearGradient(0, 0, 0, 600);
  sky.addColorStop(0, '#8ec5fc');
  sky.addColorStop(1, '#e0c3fc');
  context.fillStyle = sky;
  context.fillRect(0, 0, 900, 600);
  context.fillStyle = '#f6c48f';
  context.beginPath();
  context.arc(450, 250, 110, 0, Math.PI * 2);
  context.fill();
  context.fillStyle = '#5b6b8c';
  context.beginPath();
  context.ellipse(450, 560, 230, 190, 0, Math.PI, 0);
  context.fill();
  context.fillStyle = '#3d2c29';
  context.beginPath();
  context.arc(450, 210, 115, Math.PI, 0);
  context.fill();

  return canvas.toDataURL('image/png');
}

function drawSignature() {
  const canvas = document.createElement('canvas');
  canvas.width = 900;
  canvas.height = 400;
  const context = canvas.getContext('2d');
  context.strokeStyle = '#1b2a6b';
  context.lineWidth = 6;
  context.lineCap = 'round';
  context.lineJoin = 'round';
  context.beginPath();
  context.moveTo(180, 250);
  context.bezierCurveTo(220, 120, 260, 120, 270, 230);
  context.bezierCurveTo(280, 320, 330, 160, 360, 200);
  context.bezierCurveTo(390, 240, 400, 260, 440, 190);
  context.bezierCurveTo(470, 140, 500, 300, 540, 220);
  context.bezierCurveTo(570, 160, 610, 250, 650, 210);
  context.bezierCurveTo(690, 170, 700, 230, 730, 215);
  context.stroke();
  context.beginPath();
  context.moveTo(200, 290);
  context.bezierCurveTo(400, 270, 560, 280, 720, 260);
  context.stroke();

  return canvas.toDataURL('image/png');
}

export default {
  template: '#vue-template-wexample-symfony-design-system-demo-bundle-components-demo-image-crop-demo-image-crop',

  components: {
    ImageCrop
  },

  data() {
    return {
      cases: [
        { key: 'portrait', src: drawPortrait(), aspect: 1, round: true, area: null, preview: null },
        { key: 'signature', src: drawSignature(), aspect: null, round: false, area: null, preview: null }
      ]
    };
  },

  beforeUnmount() {
    this.cases.forEach((item) => item.preview && URL.revokeObjectURL(item.preview));
  },

  methods: {
    // Each move of the area cuts again, which is what a page sending the cut
    // would do once, on submit.
    async onChange(item, area, index) {
      item.area = area;
      const blob = await this.$refs[`crop-${index}`][0].crop({ maxWidth: 480 });

      if (item.preview) {
        URL.revokeObjectURL(item.preview);
      }

      item.preview = blob ? URL.createObjectURL(blob) : null;
    },

    pick(item, event) {
      const file = event.target.files?.[0];

      if (file) {
        item.src = URL.createObjectURL(file);
      }
    }
  }
};
</script>
