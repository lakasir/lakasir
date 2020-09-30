import route from 'ziggy';
import {Ziggy} from './ziggy.js';

export default {
  route: (name, params, absolute) => route(name, params, absolute, Ziggy),
  priceFormat: (number) => {
    const formatter = new Intl.NumberFormat('id-ID', {
      style: 'currency',
      currency: 'IDR',
      minimumFractionDigits: 2
    })
    return formatter.format(number)
  }
}
