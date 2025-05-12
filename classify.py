import sys
import pickle
import numpy as np
import warnings

warnings.filterwarnings("ignore", category=UserWarning)

print("Script started")
print("Args:", sys.argv)

if len(sys.argv) < 3:
    print("Usage: python classify.py model_file feature1 feature2 ...")
    sys.exit(1)

model_file = sys.argv[1]
features = np.array(sys.argv[2:], dtype=float).reshape(1, -1)
print("Input features:", features)

with open(model_file, 'rb') as f:
    loaded = pickle.load(f)

# Check if it's a dict bundle or just a model
if isinstance(loaded, dict) and 'model' in loaded:
    model = loaded['model']
    scaler = loaded.get('scaler', None)
    print("Loaded model (from bundle):", type(model).__name__)
elif hasattr(loaded, 'predict'):
    model = loaded
    scaler = None
    print("Loaded model (direct):", type(model).__name__)
else:
    print("Unsupported model format:", type(loaded))
    sys.exit(1)

# Apply scaling if scaler exists
if scaler:
    features = scaler.transform(features)
    print("Scaled features:", features)
else:
    print("No scaler applied.")

# Predict
predicted = model.predict(features)[0]

# Label map
classification_labels = {
    0: "not_recom",
    1: "priority",
    2: "recommend",
    3: "spec_recommend",
    4: "very-recommend"
}

label = classification_labels.get(predicted, "Unknown")
print("Prediction result:", label)
