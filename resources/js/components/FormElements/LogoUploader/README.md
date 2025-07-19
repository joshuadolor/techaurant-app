# LogoUploader Component

A reusable Vue 3 component for uploading and cropping images with preview functionality. Built with Element Plus and vue-advanced-cropper.

## Features

-   Image upload with drag & drop support
-   Built-in cropping functionality
-   Image preview
-   Customizable aspect ratio
-   Mobile responsive
-   TypeScript friendly
-   Configurable tips and text

## Basic Usage

```vue
<template>
    <LogoUploader
        v-model:logo="logo"
        v-model:logoPreview="logoPreview"
        @change="handleLogoChange"
    />
</template>

<script setup>
import { ref } from "vue";
import LogoUploader from "@/components/FormElements/LogoUploader/index.vue";

const logo = ref(null);
const logoPreview = ref("");

const handleLogoChange = ({ file, preview }) => {
    console.log("New logo uploaded:", file);
    // Handle the uploaded file
};
</script>
```

## Props

| Prop              | Type                       | Default                  | Description                           |
| ----------------- | -------------------------- | ------------------------ | ------------------------------------- |
| `logo`            | `String \| File \| Object` | `null`                   | The logo file or URL                  |
| `logoPreview`     | `String`                   | `''`                     | Preview URL for the logo              |
| `uploadText`      | `String`                   | `'Click to upload logo'` | Text shown in upload area             |
| `uploadHint`      | `String`                   | `'PNG, JPG up to 2MB'`   | Hint text below upload text           |
| `altText`         | `String`                   | `'Uploaded logo'`        | Alt text for uploaded image           |
| `showTips`        | `Boolean`                  | `true`                   | Whether to show tips section          |
| `tipsTitle`       | `String`                   | `'Logo Tips'`            | Title for tips section                |
| `tips`            | `Array`                    | `[...]`                  | Array of tip strings                  |
| `cropperTitle`    | `String`                   | `'Crop Image'`           | Title for cropper dialog              |
| `imageType`       | `String`                   | `'Logo'`                 | Type of image (used in cropper)       |
| `cropAspectRatio` | `Number`                   | `1`                      | Aspect ratio for cropper (1 = square) |
| `enableCropping`  | `Boolean`                  | `true`                   | Enable/disable cropping functionality |

## Events

| Event                | Payload                           | Description                  |
| -------------------- | --------------------------------- | ---------------------------- |
| `update:logo`        | `File`                            | Emitted when logo changes    |
| `update:logoPreview` | `String`                          | Emitted when preview changes |
| `change`             | `{ file: File, preview: String }` | Emitted when image changes   |

## Examples

### Profile Picture Upload

```vue
<LogoUploader
    v-model:logo="profilePicture"
    v-model:logoPreview="profilePreview"
    uploadText="Upload Profile Picture"
    uploadHint="JPG, PNG up to 5MB"
    imageType="Profile Picture"
    cropperTitle="Crop Profile Picture"
    :tips="[
        'Use a clear headshot',
        'Square images work best',
        'Good lighting recommended',
    ]"
/>
```

### Product Image Upload (Rectangular)

```vue
<LogoUploader
    v-model:logo="productImage"
    v-model:logoPreview="productPreview"
    uploadText="Upload Product Image"
    uploadHint="JPG, PNG, WebP up to 10MB"
    imageType="Product Image"
    :cropAspectRatio="16 / 9"
    cropperTitle="Crop Product Image"
    tipsTitle="Image Tips"
    :tips="[
        'Use high resolution (at least 800x450px)',
        '16:9 aspect ratio recommended',
        'Show product clearly',
    ]"
/>
```

### Simple Upload Without Cropping

```vue
<LogoUploader
    v-model:logo="simpleImage"
    v-model:logoPreview="simplePreview"
    :enableCropping="false"
    :showTips="false"
    uploadText="Select Image"
    uploadHint="Any image format"
/>
```

### In a Form with Validation

```vue
<template>
    <BaseFormItem label="Company Logo" prop="logo">
        <LogoUploader
            v-model:logo="form.logo"
            v-model:logoPreview="form.logoPreview"
            uploadText="Upload Company Logo"
            cropperTitle="Crop Company Logo"
        />
    </BaseFormItem>
</template>
```

## Styling

The component uses CSS custom properties that can be overridden:

```css
.logo-uploader {
    --border-color: #d9d9d9;
    --hover-border-color: #f08a5c;
    --background-color: #fafafa;
    --hover-background-color: #fff5f2;
    --icon-color: #f08a5c;
    --text-color: #2c3e50;
    --hint-color: #6c757d;
}
```

## File Size and Format Support

-   **Supported formats**: JPG, JPEG, PNG, WebP, GIF
-   **Recommended size**: Under 2MB for optimal performance
-   **Output format**: PNG (when cropping is enabled)

## Browser Compatibility

-   Modern browsers with File API support
-   Mobile browsers (iOS Safari 10+, Chrome Mobile)
-   Desktop browsers (Chrome 60+, Firefox 55+, Safari 12+)
