// create format price function
const formatPrice = (price: number) => {
  const formatter = new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    minimumFractionDigits: 0
  })

  return formatter.format(price)
}

export {
  formatPrice
}
