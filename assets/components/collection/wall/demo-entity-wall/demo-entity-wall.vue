<script>
import AbstractEntityWall from '@wexample/symfony-design-system/components/collection/wall/abstract-entity-wall/abstract-entity-wall.vue';

// Landscapes, portraits and squares, in the order a camera roll mixes them.
const RATIOS = [[4, 3], [3, 4], [1, 1], [16, 9], [2, 3], [3, 2], [9, 16], [5, 4]];
// Two pages: the first one read with the page, the second one as the wall
// is scrolled — enough to see it come.
const DEMO_PICTURES = Array.from({ length: 24 }, (value, index) => {
  const [width, height] = RATIOS[(index * 5) % RATIOS.length];

  return {
    id: index + 1,
    name: `Picture ${String(index + 1).padStart(2, '0')} · ${width}:${height}`,
    width: width * 300,
    height: height * 300,
    hue: (index * 47) % 360,
  };
});

export default {
  extends: AbstractEntityWall,

  template: '#vue-template-wexample-symfony-design-system-demo-bundle-components-collection-wall-demo-entity-wall-demo-entity-wall',

  data() {
    return {
      pageLength: 12
    };
  },

  methods: {
    getEntityClass() {
      return null;
    },

    // Stands in for the API: the pages a paginated endpoint would answer,
    // after the time a network would take, so the wall is seen growing.
    getEntityRepository() {
      return {
        async fetchListPaginated({ page = 0, length }) {
          await new Promise((resolve) => setTimeout(resolve, 400));
          const offset = page * length;

          return {
            items: DEMO_PICTURES.slice(offset, offset + length),
            pagination: {
              page,
              length,
              total: DEMO_PICTURES.length,
              pagesCount: Math.ceil(DEMO_PICTURES.length / length),
              hasMore: offset + length < DEMO_PICTURES.length,
            },
          };
        },
      };
    },

    getTileConfiguration(entity) {
      return {
        ...AbstractEntityWall.methods.getTileConfiguration.call(this, entity),
        // Drawn by the design system's fixtures, at the picture's ratio.
        src: this.app.getServiceOrFail('routing').path('wexample_design_system_fixtures_picture_svg', {
          width: entity.width,
          height: entity.height,
          hue: entity.hue,
        }),
        href: '#',
      };
    }
  }
};
</script>
