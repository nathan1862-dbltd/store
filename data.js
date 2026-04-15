/*
 * data.js
 *
 * This file defines helper functions for persisting application data to
 * localStorage and includes a one‑time initialization routine that
 * preloads the store with sample categories, brands, products,
 * coupons, rewards and gifts.  All data structures live under a
 * single key (`ecommerce_data`) to reduce namespace pollution.
 */

// The main key under which we store all persistent data.  This makes
// it easy to snapshot the entire store or migrate it later.  If you
// rename this key you must also adjust every call to getData() and
// setData().
const STORAGE_KEY = 'ecommerce_data';

/**
 * Returns the entire persisted data object.  If no data exists yet
 * then an empty object is returned.
 */
function getAllData() {
  try {
    const raw = localStorage.getItem(STORAGE_KEY);
    return raw ? JSON.parse(raw) : {};
  } catch (err) {
    console.error('Error reading from localStorage', err);
    return {};
  }
}

/**
 * Persists the supplied object under the STORAGE_KEY.  Call this
 * whenever you mutate the data returned from getAllData().
 *
 * @param {Object} data The complete data object to persist.
 */
function setAllData(data) {
  localStorage.setItem(STORAGE_KEY, JSON.stringify(data));
}

/**
 * Retrieve a named subkey from the persisted store.  If the key does
 * not exist then the supplied default value will be returned.  This
 * helper never mutates the store by itself.
 *
 * @param {string} key The property of the root data object you wish
 *   to access.
 * @param {any} defaultValue A fallback value used when the key is
 *   missing.
 */
function getData(key, defaultValue) {
  const data = getAllData();
  return data.hasOwnProperty(key) ? data[key] : defaultValue;
}

/**
 * Set a named subkey on the persisted store.  The updated root
 * object is written back to localStorage immediately.
 *
 * @param {string} key The property of the root data object to set.
 * @param {any} value The value to assign.
 */
function setData(key, value) {
  const data = getAllData();
  data[key] = value;
  setAllData(data);
}

/**
 * Generates a pseudo unique identifier by combining the current
 * timestamp with a random component.  These IDs are used for
 * products, orders and other entities.  They are not secure, but
 * sufficient for client side storage.
 */
function generateId(prefix = '') {
  const rand = Math.floor(Math.random() * 1e8).toString(36);
  return prefix + Date.now().toString(36) + rand;
}

/**
 * Initialise the persistent store if it hasn’t been set up before.
 * This function must be called at least once on page load.  It
 * populates default data such as sample categories, brands, products,
 * coupons, rewards and birthday gift identifiers.  On subsequent
 * calls it does nothing.
 */
function initSampleData() {
  // Check if we've already initialised.  A flag is stored under the
  // `initialised` key within the root object.
  const data = getAllData();
  if (data.initialised) {
    return;
  }

  // Sample categories.  These represent broad groupings that users
  // will see on the home page.  Feel free to extend or modify these.
  const categories = [
    { id: generateId('cat_'), name: 'Electronics' },
    { id: generateId('cat_'), name: 'Fashion' },
    { id: generateId('cat_'), name: 'Home & Kitchen' },
    { id: generateId('cat_'), name: 'Sports' },
    { id: generateId('cat_'), name: 'Toys' }
  ];

  // Sample brands.  Brands help users filter products further.  These
  // are purely illustrative.
  const brands = [
    { id: generateId('brand_'), name: 'Acme' },
    { id: generateId('brand_'), name: 'Globex' },
    { id: generateId('brand_'), name: 'Umbrella' },
    { id: generateId('brand_'), name: 'Soylent' },
    { id: generateId('brand_'), name: 'Initech' }
  ];

  // Helper function to pick a random element from an array.
  function randomChoice(arr) {
    return arr[Math.floor(Math.random() * arr.length)];
  }

  // Generate a set of sample products.  Each product references a
  // category and brand.  Prices are in Myanmar kyats (Ks) but could be
  // anything—these numbers are examples.  Images will be assigned
  // later via placeholders and can be customised by editing the
  // products array below.
  const products = [];
  const productNames = [
    'Wireless Headphones',
    'Running Shoes',
    'Blender',
    'Yoga Mat',
    'Building Blocks Set',
    'Smartphone',
    'Designer Handbag',
    'Coffee Maker',
    'Basketball',
    'Doll House'
  ];

  const productDescriptions = [
    'High quality and durable.',
    'A must‑have for any enthusiast.',
    'Sleek design with top performance.',
    'Made from premium materials.',
    'Limited edition item.',
    'Perfect for gift giving.',
    'Best in class.',
    'An essential household item.',
    'Kids will love this.',
    'Upgraded version with new features.'
  ];

  // We'll reuse a small set of placeholder images.  The actual
  // generation happens outside this file using the imagegen tool.
  const placeholderImages = [
    'assets/product1.png',
    'assets/product2.png',
    'assets/product3.png',
    'assets/product4.png',
    'assets/product5.png'
  ];

  for (let i = 0; i < 30; i++) {
    const name = randomChoice(productNames);
    const desc = randomChoice(productDescriptions);
    const price = Math.floor(Math.random() * 90000 + 10000); // 10,000–100,000 Ks
    const category = randomChoice(categories);
    const brand = randomChoice(brands);
    const image = randomChoice(placeholderImages);
    products.push({
      id: generateId('prod_'),
      name: name,
      description: desc,
      price: price,
      category_id: category.id,
      brand_id: brand.id,
      image: image,
      stock: Math.floor(Math.random() * 100 + 1)
    });
  }

  // Sample coupons.  Codes are case insensitive.  Each coupon can be
  // a percentage discount or a fixed amount off.  The expiry date
  // uses ISO format (YYYY‑MM‑DD).  `min_purchase` defines the minimum
  // cart total required to apply the coupon (before discount).
  const coupons = [
    {
      code: 'WELCOME10',
      discount_type: 'percent',
      value: 10,
      expiry_date: '2025-12-31',
      min_purchase: 0,
      used_by: []
    },
    {
      code: 'FASHION5',
      discount_type: 'amount',
      value: 5000,
      expiry_date: '2026-03-31',
      min_purchase: 20000,
      used_by: []
    },
    {
      code: 'BDAY20',
      discount_type: 'percent',
      value: 20,
      expiry_date: '2027-12-31',
      min_purchase: 50000,
      used_by: []
    }
  ];

  // Sample rewards.  Users can exchange loyalty points for these
  // rewards.  A reward can either grant a coupon (discount on
  // purchases) or provide a free product.  The `reward_value` field
  // holds the corresponding coupon code or product id.  When a
  // reward is claimed the user’s points decrease accordingly and the
  // reward is recorded on their account.
  const rewards = [
    {
      id: generateId('rew_'),
      name: '50% off your next purchase',
      description: 'Get half off a single future order.',
      points_required: 500,
      type: 'coupon',
      reward_value: 'HALFOFF'
    },
    {
      id: generateId('rew_'),
      name: 'Free Yoga Mat',
      description: 'Redeem this reward to receive a free yoga mat.',
      points_required: 300,
      type: 'product',
      reward_value: null // We'll pick a yoga mat product later
    },
    {
      id: generateId('rew_'),
      name: 'Ks 10,000 voucher',
      description: 'Take Ks 10,000 off your next order.',
      points_required: 200,
      type: 'coupon',
      reward_value: 'TENKOFF'
    }
  ];

  // Assign a specific product id to the yoga mat reward.  If no
  // product with "Yoga Mat" exists then assign the first product.
  const yoga = products.find(p => p.name.includes('Yoga Mat'));
  if (yoga) {
    rewards.find(r => r.name === 'Free Yoga Mat').reward_value = yoga.id;
  } else {
    rewards.find(r => r.name === 'Free Yoga Mat').reward_value = products[0].id;
  }

  // Sample birthday gift options.  We'll choose three random products
  // from the catalogue.  These ids will be checked when offering
  // birthday gifts during checkout.
  const giftIds = [];
  const shuffled = [...products].sort(() => Math.random() - 0.5);
  giftIds.push(shuffled[0].id);
  giftIds.push(shuffled[1].id);
  giftIds.push(shuffled[2].id);

  // Persist everything to localStorage.  We also mark the
  // initialization flag so that future calls do not overwrite user
  // modifications.
  setAllData({
    initialised: true,
    categories,
    brands,
    products,
    coupons,
    rewards,
    giftIds,
    users: [],
    orders: []
  });
}

// When this script is loaded it automatically initialises the
// database if needed.  Because scripts are included at the bottom of
// pages, this will run as soon as the DOM is ready but before any
// page‑specific logic executes.
initSampleData();
