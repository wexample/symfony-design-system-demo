import AbstractApiEntity from '@wexample/js-api/Common/AbstractApiEntity';
import schema from '../data/entity/demo_room.json';

export default class DemoRoom extends AbstractApiEntity {
  static readonly entityName = 'demoRoom';

  static retrieveEntitySchema() {
    return schema;
  }
}
