import { createApp } from 'vue'
import RestaurantApp from './pages/Restaurant/RestaurantApp.vue'

// Element Plus imports
import ElementPlus from 'element-plus'
import 'element-plus/dist/index.css'

// Get restaurant data from the blade template
const restaurantDataElement = document.getElementById('restaurant-data')
const restaurantData = restaurantDataElement ? JSON.parse(restaurantDataElement.textContent) : {}

// Create Vue application
const app = createApp(RestaurantApp, {
    restaurantData
})

// Configure Element Plus
app.use(ElementPlus)

// Mount the app
app.mount('#restaurant-app')

// Hide loading screen once app is mounted
app._container.addEventListener('vue:mounted', () => {
    const loading = document.getElementById('loading')
    if (loading) {
        loading.style.opacity = '0'
        setTimeout(() => loading.remove(), 300)
    }
})

// Simple fade out for loading
window.addEventListener('load', () => {
    setTimeout(() => {
        const loading = document.getElementById('loading')
        if (loading) {
            loading.style.opacity = '0'
            setTimeout(() => loading.remove(), 300)
        }
    }, 500)
})