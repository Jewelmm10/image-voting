# Image Contest Voting Plugin

## Description
The **Image Contest Voting Plugin** allows users to create a custom post type for image contests, featuring a voting system. Each post includes a title, featured image (thumbnail), a vote count. Users can vote for their favorite images, and votes are updated dynamically without refreshing the page.

---

## Features
- Custom post type: `Image Contest`
- Featured image
- Voting system with real-time updates
- Total votes displayed dynamically for each image
- Shortcode to display voting cards

---

## Installation
1. Download the plugin files and place them in your WordPress `wp-content/plugins/` directory.
2. Activate the plugin via the WordPress Admin Dashboard under **Plugins**.

---

## Usage

### Adding an Image Contest Post
1. Navigate to **Image Contest** in the WordPress Admin Menu.
2. Add a new post.
3. Add a title and a featured image.
5. Publish the post.

### Displaying the Contest
To display the image contest on the frontend:
1. Use the shortcode `[icp_voting]` in page, or widget.
2. The voting cards will display with the title, images, vote button, and vote counts.

---

## Shortcode
`[icp_voting]`

### Output
The shortcode displays all image contest posts in a card layout with:
- Title
- Featured image
- Vote button
- Vote count for the image and total votes across all images

---

## JavaScript Features
Real-time vote count updates are handled using AJAX, ensuring a seamless user experience.

---

## File Structure
```plaintext
image-voting-plugin/             
├── icp-style.js                # Style for voting card design
├── icp-voting.js               # Script for handling voting functionality
├── index.php                   # Plugin main file
├── readme.md                   # Readme file
```

---

## Development Notes
- AJAX endpoints are secured with nonces to prevent unauthorized access.
- Fully compatible with WordPress coding standards.

---

