/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    // WordPress theme files
    './*.php',
    './templates/**/*.php',
    './blocks/**/*.php',
    './functions/**/*.php',
    
    // JavaScript files
    './assets/js/**/*.js',
    
    // ACF JSON files (for dynamic content)
    './acf-json/**/*.json',
    
    // Template parts
    './template-parts/**/*.php',
    './parts/**/*.php',
    
    // Any other PHP files in the theme
    './**/*.php',
    
    // Exclude node_modules and vendor
    '!./node_modules/**',
    '!./vendor/**',
  ],
  theme: {
    extend: {
      // Custom container settings (used in your CSS)
      container: {
        center: true,
        padding: {
          DEFAULT: '1rem',
          sm: '2rem',
          lg: '4rem',
          xl: '5rem',
          '2xl': '6rem',
        },
      },
      
      // WordPress specific utilities (used in your CSS)
      typography: {
        DEFAULT: {
          css: {
            maxWidth: 'none',
            color: '#374151',
            a: {
              color: '#3b82f6',
              '&:hover': {
                color: '#1d4ed8',
              },
            },
            h1: {
              color: '#111827',
            },
            h2: {
              color: '#111827',
            },
            h3: {
              color: '#111827',
            },
            h4: {
              color: '#111827',
            },
            h5: {
              color: '#111827',
            },
            h6: {
              color: '#111827',
            },
            blockquote: {
              borderLeftColor: '#d1d5db',
            },
            code: {
              color: '#dc2626',
            },
            'code::before': {
              content: '""',
            },
            'code::after': {
              content: '""',
            },
          },
        },
      },
    },
  },
  plugins: [
    // Add typography plugin (used in your CSS with prose classes)
    require('@tailwindcss/typography'),
    
    // Add forms plugin for better form styling
    require('@tailwindcss/forms'),
  ],
  // Disable preflight for WordPress compatibility
  corePlugins: {
    preflight: false,
  },
}