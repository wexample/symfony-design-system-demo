import AbstractApiRepository from '@wexample/js-api/Common/AbstractApiRepository';
import DemoMessage from '../Entity/DemoMessage.js';

export default class DemoMessageRepository extends AbstractApiRepository<DemoMessage> {
  static getEntityType() {
    return DemoMessage;
  }
}
