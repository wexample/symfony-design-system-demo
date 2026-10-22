import AbstractApiEntity from '@wexample/js-api-entity/Common/AbstractApiEntity';
import schema from '../data/entity/demo_message.json';

export default class DemoMessage extends AbstractApiEntity {
  static readonly entityName = 'demoMessage';

  static retrieveEntitySchema() {
    return schema;
  }
}
